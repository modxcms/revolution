<?php
/**
 * New Install specific DB script
 *
 * @var modInstallRunner $this
 * @var modInstall $install
 * @var xPDO $modx
 * @var modInstallSettings $settings
 * 
 * @package modx
 * @subpackage setup
 */
/* add settings_version */
$currentVersion = include MODX_CORE_PATH . 'docs/version.inc.php';

$settings_version = $modx->newObject('modSystemSetting');
$settings_version->set('key','settings_version');
$settings_version->set('value', $currentVersion['full_version']);
$settings_version->set('xtype','textfield');
$settings_version->set('namespace','core');
$settings_version->set('area','system');
$settings_version->save();

$settings_distro = $modx->newObject('modSystemSetting');
$settings_distro->set('key','settings_distro');
$settings_distro->set('value', trim($currentVersion['distro'], '@'));
$settings_distro->set('xtype','textfield');
$settings_distro->set('namespace','core');
$settings_distro->set('area','system');
$settings_distro->save();

/* add default admin user */
/** @var modUser $user */
$user = $modx->newObject('modUser');
$user->set('username', $settings->get('cmsadmin'));
$user->set('password', $settings->get('cmspassword'));
$user->setSudo(true);
$saved = $user->save();

if ($saved) {
    /** @var modUserProfile $userProfile */
    $userProfile = $modx->newObject('modUserProfile');
    $userProfile->set('internalKey', $user->get('id'));
    $userProfile->set('fullname', $install->lexicon('default_admin_user'));
    $userProfile->set('email', $settings->get('cmsadminemail'));
    $saved = $userProfile->save();
    if ($saved) {
        /** @var modUserGroupMember $userGroupMembership */
        $userGroupMembership = $modx->newObject('modUserGroupMember');
        $userGroupMembership->set('user_group', 1);
        $userGroupMembership->set('member', $user->get('id'));
        $userGroupMembership->set('role', 2);
        $saved = $userGroupMembership->save();

        $user->set('primary_group',1);
        $user->save();
    }
    if ($saved) {
        /** @var modSystemSetting $emailSender */
        $emailSender = $modx->getObject('modSystemSetting', array('key' => 'emailsender'));
        if ($emailSender) {
            $emailSender->set('value', $settings->get('cmsadminemail'));
            $saved = $emailSender->save();
        }
    }
}
if (!$saved) {
    $this->addResult(modInstallRunner::RESULT_ERROR,'<p class="notok">'.$install->lexicon('dau_err_save').'<br />' . print_r($modx->errorInfo(), true) . '</p>');
} else {
    $this->addResult(modInstallRunner::RESULT_SUCCESS,'<p class="ok">'.$install->lexicon('dau_saved').'</p>');
}

/* set new_folder_permissions/new_file_permissions if specified */
if ($install->settings->get('new_folder_permissions')) {
    /** @var modSystemSetting $settingsFolderPerms */
    $settingsFolderPerms = $modx->newObject('modSystemSetting');
    $settingsFolderPerms->set('key', 'new_folder_permissions');
    $settingsFolderPerms->set('value', $settings->get('new_folder_permissions'));
    $settingsFolderPerms->save();
}
if ($install->settings->get('new_file_permissions')) {
    /** @var modSystemSetting $settingsFilePerms */
    $settingsFilePerms = $modx->newObject('modSystemSetting');
    $settingsFilePerms->set('key', 'new_file_permissions');
    $settingsFilePerms->set('value', $settings->get('new_file_permissions'));
    $settingsFilePerms->save();
}

/* setup load only anonymous ACL */
/** @var modAccessPolicy $loadOnly */
$loadOnly = $modx->getObject('modAccessPolicy',array(
    'name' => 'Load Only',
));
if ($loadOnly) {
    /** @var modAccessContext $access */
    $access= $modx->newObject('modAccessContext');
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
$adminPolicy = $modx->getObject('modAccessPolicy',array(
    'name' => 'Administrator',
));
/** @var modUserGroup $adminGroup */
$adminGroup = $modx->getObject('modUserGroup',array(
    'name' => 'Administrator',
));
if ($adminPolicy && $adminGroup) {
    /** @var modAccessContext $access */
    $access= $modx->newObject('modAccessContext');
    $access->fromArray(array(
      'target' => 'mgr',
      'principal_class' => 'modUserGroup',
      'principal' => $adminGroup->get('id'),
      'authority' => 0,
      'policy' => $adminPolicy->get('id'),
    ));
    $access->save();
    unset($access);

    $access= $modx->newObject('modAccessContext');
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
$template = $modx->newObject('modTemplate');
$template->fromArray(array(
    'templatename' => $install->lexicon('base_template'),
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
    $resource = $modx->newObject('modResource');
    $resource->fromArray(array(
        'pagetitle' => $install->lexicon('home'),
        'alias' => 'index',
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
    $setting = $modx->getObject('modSystemSetting',array(
        'key' => 'use_multibyte',
    ));
    if (!$setting) {
        $setting = $modx->newObject('modSystemSetting');
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
$language = $settings->get('language','en');
if ($language != 'en') {
    /* cultureKey */
    $setting = $modx->getObject('modSystemSetting',array(
        'key' => 'cultureKey',
    ));
    if (!$setting) {
        $setting = $modx->newObject('modSystemSetting');
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
    $setting = $modx->getObject('modSystemSetting',array(
        'key' => 'manager_language',
    ));
    if (!$setting) {
        $setting = $modx->newObject('modSystemSetting');
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
    $setting = $modx->getObject('modSystemSetting',array(
        'key' => 'manager_lang_attribute',
    ));
    if (!$setting) {
        $setting = $modx->newObject('modSystemSetting');
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
