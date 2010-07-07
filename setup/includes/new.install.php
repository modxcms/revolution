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
$settings_version->save();

/* add default admin user */
$user = $this->xpdo->newObject('modUser');
$user->set('username', $this->settings->get('cmsadmin'));
$user->set('password', md5($this->settings->get('cmspassword')));
$saved = $user->save();

if ($saved) {
    $userProfile = $this->xpdo->newObject('modUserProfile');
    $userProfile->set('internalKey', $user->get('id'));
    $userProfile->set('fullname', $this->lexicon['default_admin_user']);
    $userProfile->set('email', $this->settings->get('cmsadminemail'));
    $userProfile->set('role', 1);
    $saved = $userProfile->save();
    if ($saved) {
        $userGroupMembership = $this->xpdo->newObject('modUserGroupMember');
        $userGroupMembership->set('user_group', 1);
        $userGroupMembership->set('member', $user->get('id'));
        $userGroupMembership->set('role', 2);
        $saved = $userGroupMembership->save();
    }
    if ($saved) {
        $emailsender = $this->xpdo->getObject('modSystemSetting', array('key' => 'emailsender'));
        if ($emailsender) {
            $emailsender->set('value', $this->settings->get('cmsadminemail'));
            $saved = $emailsender->save();
        }
    }
}
if (!$saved) {
    $results[] = array (
        'class' => 'error',
        'msg' => '<p class="notok">'.$this->lexicon['dau_err_save'].'<br />' . print_r($this->xpdo->errorInfo(), true) . '</p>'
    );
} else {
    $results[] = array (
        'class' => 'success',
        'msg' => '<p class="ok">'.$this->lexicon['dau_saved'].'</p>'
    );
}

/* set new_folder_permissions/new_file_permissions if specified */
if ($this->settings->get('new_folder_permissions')) {
    $settings_folder_perms = $this->xpdo->newObject('modSystemSetting');
    $settings_folder_perms->set('key', 'new_folder_permissions');
    $settings_folder_perms->set('value', $this->settings->get('new_folder_permissions'));
    $settings_folder_perms->save();
}
if ($this->settings->get('new_file_permissions')) {
    $settings_file_perms = $this->xpdo->newObject('modSystemSetting');
    $settings_file_perms->set('key', 'new_file_permissions');
    $settings_file_perms->set('value', $this->settings->get('new_file_permissions'));
    $settings_file_perms->save();
}

/* compress and concat JS on new installs */
if (defined('MODX_SETUP_KEY') && MODX_SETUP_KEY != '@svn') {
    $concatJavascript = $this->xpdo->getObject('modSystemSetting', array(
        'key' => 'concat_js',
    ));
    if ($concatJavascript) {
        $concatJavascript->set('value',1);
        $concatJavascript->save();
    }
    $compressJavascript = $this->xpdo->getObject('modSystemSetting', array(
        'key' => 'compress_js',
    ));
    if ($compressJavascript) {
        $compressJavascript->set('value',1);
        $compressJavascript->save();
    }
    $compressCss = $this->xpdo->getObject('modSystemSetting', array(
        'key' => 'compress_css',
    ));
    if ($compressCss) {
        $compressCss->set('value',1);
        $compressCss->save();
    }
    unset($concatJavascript,$compressJavascript,$compressCss);
}

/* setup load only anonymous ACL */
$loadOnly = $this->xpdo->getObject('modAccessPolicy',array(
    'name' => 'Load Only',
));
if ($loadOnly) {
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
$adminPolicy = $this->xpdo->getObject('modAccessPolicy',array(
    'name' => 'Administrator',
));
$adminGroup = $this->xpdo->getObject('modUserGroup',array(
    'name' => 'Administrator',
));
if ($adminPolicy && $adminGroup) {
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
$template = $this->xpdo->newObject('modTemplate');
$template->fromArray(array(
    'templatename' => $this->lexicon['base_template'],
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
    $resource = $this->xpdo->newObject('modResource');
    $resource->fromArray(array(
        'pagetitle' => $this->lexicon['home'],
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

return true;