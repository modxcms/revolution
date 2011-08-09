<?php
/**
 * New Install specific DB script
 *
 * @package modx
 * @subpackage setup
 */
/* add settings_version */
$currentVersion = include MODX_CORE_PATH . 'docs/version.inc.php';

$settings_version = $this->xpdo->newObject('modSystemSetting');
$settings_version->set('key','settings_version');
$settings_version->set('value', $currentVersion['full_version']);
$settings_version->set('xtype','textfield');
$settings_version->set('namespace','core');
$settings_version->set('area','system');
$settings_version->save();

$settings_distro = $this->xpdo->newObject('modSystemSetting');
$settings_distro->set('key','settings_distro');
$settings_distro->set('value', trim($currentVersion['distro'], '@'));
$settings_distro->set('xtype','textfield');
$settings_distro->set('namespace','core');
$settings_distro->set('area','system');
$settings_distro->save();

/* add default admin user */
/** @var modUser $user */
$user = $this->xpdo->newObject('modUser');
$user->set('username', $this->settings->get('cmsadmin'));
$user->set('password', $this->settings->get('cmspassword'));
$saved = $user->save();

if ($saved) {
    /** @var modUserProfile $userProfile */
    $userProfile = $this->xpdo->newObject('modUserProfile');
    $userProfile->set('internalKey', $user->get('id'));
    $userProfile->set('fullname', $this->lexicon('default_admin_user'));
    $userProfile->set('email', $this->settings->get('cmsadminemail'));
    $saved = $userProfile->save();
    if ($saved) {
        /** @var modUserGroupMember $userGroupMembership */
        $userGroupMembership = $this->xpdo->newObject('modUserGroupMember');
        $userGroupMembership->set('user_group', 1);
        $userGroupMembership->set('member', $user->get('id'));
        $userGroupMembership->set('role', 2);
        $saved = $userGroupMembership->save();

        $user->set('primary_group',1);
        $user->save();
    }
    if ($saved) {
        /** @var modSystemSetting $emailSender */
        $emailSender = $this->xpdo->getObject('modSystemSetting', array('key' => 'emailsender'));
        if ($emailSender) {
            $emailSender->set('value', $this->settings->get('cmsadminemail'));
            $saved = $emailSender->save();
        }
    }
}
if (!$saved) {
    $results[] = array (
        'class' => 'error',
        'msg' => '<p class="notok">'.$this->lexicon('dau_err_save').'<br />' . print_r($this->xpdo->errorInfo(), true) . '</p>'
    );
} else {
    $results[] = array (
        'class' => 'success',
        'msg' => '<p class="ok">'.$this->lexicon('dau_saved').'</p>'
    );
}

/* set new_folder_permissions/new_file_permissions if specified */
if ($this->settings->get('new_folder_permissions')) {
    /** @var modSystemSetting $settingsFolderPerms */
    $settingsFolderPerms = $this->xpdo->newObject('modSystemSetting');
    $settingsFolderPerms->set('key', 'new_folder_permissions');
    $settingsFolderPerms->set('value', $this->settings->get('new_folder_permissions'));
    $settingsFolderPerms->save();
}
if ($this->settings->get('new_file_permissions')) {
    /** @var modSystemSetting $settingsFilePerms */
    $settingsFilePerms = $this->xpdo->newObject('modSystemSetting');
    $settingsFilePerms->set('key', 'new_file_permissions');
    $settingsFilePerms->set('value', $this->settings->get('new_file_permissions'));
    $settingsFilePerms->save();
}

/* setup load only anonymous ACL */
/** @var modAccessPolicy $loadOnly */
$loadOnly = $this->xpdo->getObject('modAccessPolicy',array(
    'name' => 'Load Only',
));
if ($loadOnly) {
    /** @var modAccessContext $access */
    $access= $this->xpdo->newObject('modAccessContext');
    $access->fromArray(array(
      'target' => 'web',
      'principal_class' => 'modUserGroup',
      'principal' => 0,
      'authority' => 9999,
      'policy' => $loadOnly->get('id'),
    ));
    $access->save();
    unset($access);
}
unset($loadOnly);


/* setup default admin ACLs */
/** @var modAccessPolicy $adminPolicy */
$adminPolicy = $this->xpdo->getObject('modAccessPolicy',array(
    'name' => 'Administrator',
));
/** @var modUserGroup $adminGroup */
$adminGroup = $this->xpdo->getObject('modUserGroup',array(
    'name' => 'Administrator',
));
if ($adminPolicy && $adminGroup) {
    /** @var modAccessContext $access */
    $access= $this->xpdo->newObject('modAccessContext');
    $access->fromArray(array(
      'target' => 'mgr',
      'principal_class' => 'modUserGroup',
      'principal' => $adminGroup->get('id'),
      'authority' => 0,
      'policy' => $adminPolicy->get('id'),
    ));
    $access->save();
    unset($access);

    $access= $this->xpdo->newObject('modAccessContext');
    $access->fromArray(array(
      'target' => 'web',
      'principal_class' => 'modUserGroup',
      'principal' => $adminGroup->get('id'),
      'authority' => 0,
      'policy' => $adminPolicy->get('id'),
    ));
    $access->save();
    unset($access);
}
unset($adminPolicy,$adminGroup);

/* add base template and home resource */
/** @var modTemplate $template */
$template = $this->xpdo->newObject('modTemplate');
$template->fromArray(array(
    'templatename' => $this->lexicon('base_template'),
    'content' => '<html>
<head>
<title>[[++site_name]] - [[*pagetitle]]</title>
<base href="[[++site_url]]" />
</head>
<body>
[[*content]]
</body>
</html>',
));
if ($template->save()) {
    /** @var modResource $resource */
    $resource = $this->xpdo->newObject('modResource');
    $resource->fromArray(array(
        'pagetitle' => $this->lexicon('home'),
        'alias' => '',
        'contentType' => 'text/html',
        'type' => 'document',
        'published' => true,
        'content' => '',
        'template' => $template->get('id'),
        'searchable' => true,
        'cacheable' => true,
        'createdby' => 1,
        'hidemenu' => false,
        'class_key' => 'modDocument',
        'context_key' => 'web',
        'content_type' => 1,
    ));
    $resource->save();
}

/* check for mb extension, set setting accordingly */
$usemb = function_exists('mb_strlen');
if ($usemb) {
    /** @var modSystemSetting $setting */
    $setting = $this->xpdo->getObject('modSystemSetting',array(
        'key' => 'use_multibyte',
    ));
    if (!$setting) {
        $setting = $this->xpdo->newObject('modSystemSetting');
        $setting->fromArray(array(
            'key' => 'use_multibyte',
            'namespace' => 'core',
            'xtype' => 'combo-boolean',
            'area' => 'language',
        ));
    }
    $setting->set('value',1);
    $setting->save();
}

/* if language != en, set cultureKey, manager_language, manager_lang_attribute to it */
$language = $this->settings->get('language','en');
if ($language != 'en') {
    /* cultureKey */
    $setting = $this->xpdo->getObject('modSystemSetting',array(
        'key' => 'cultureKey',
    ));
    if (!$setting) {
        $setting = $this->xpdo->newObject('modSystemSetting');
        $setting->fromArray(array(
            'key' => 'cultureKey',
            'namespace' => 'core',
            'xtype' => 'textfield',
            'area' => 'language',
        ));
    }
    $setting->set('value',$language);
    $setting->save();

    /* manager_language */
    $setting = $this->xpdo->getObject('modSystemSetting',array(
        'key' => 'manager_language',
    ));
    if (!$setting) {
        $setting = $this->xpdo->newObject('modSystemSetting');
        $setting->fromArray(array(
            'key' => 'manager_language',
            'namespace' => 'core',
            'xtype' => 'textfield',
            'area' => 'language',
        ));
    }
    $setting->set('value',$language);
    $setting->save();

    /* manager_lang_attribute */
    $setting = $this->xpdo->getObject('modSystemSetting',array(
        'key' => 'manager_lang_attribute',
    ));
    if (!$setting) {
        $setting = $this->xpdo->newObject('modSystemSetting');
        $setting->fromArray(array(
            'key' => 'manager_lang_attribute',
            'namespace' => 'core',
            'xtype' => 'textfield',
            'area' => 'language',
        ));
    }
    $setting->set('value',$language);
    $setting->save();
}

return true;
