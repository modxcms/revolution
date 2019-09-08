<?php
/**
 * New Install specific DB script
 *
 * @var modInstallRunner $this
 * @var modInstall $install
 * @var \xPDO\xPDO $modx
 * @var modInstallSettings $settings
 *
 * @package modx
 * @subpackage setup
 */

use MODX\Revolution\modAccessContext;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modDocument;
use MODX\Revolution\modResource;
use MODX\Revolution\modSystemSetting;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserProfile;

/* add settings_version */
$currentVersion = include MODX_CORE_PATH . 'docs/version.inc.php';

$settings_version = $modx->newObject(modSystemSetting::class);
$settings_version->set('key','settings_version');
$settings_version->set('value', $currentVersion['full_version']);
$settings_version->set('xtype','textfield');
$settings_version->set('namespace','core');
$settings_version->set('area','system');
$settings_version->save();

$settings_distro = $modx->newObject(modSystemSetting::class);
$settings_distro->set('key','settings_distro');
$settings_distro->set('value', trim($currentVersion['distro'], '@'));
$settings_distro->set('xtype','textfield');
$settings_distro->set('namespace','core');
$settings_distro->set('area','system');
$settings_distro->save();

/* add default admin user*/

/** @var modUser $user */
$user = $modx->newObject(modUser::class);
$user->set('username', $settings->get('cmsadmin'));
$user->set('password', $settings->get('cmspassword'));
$user->setSudo(true);
$saved = $user->save();

if ($saved) {
    /** @var modUserProfile $userProfile */
    $userProfile = $modx->newObject(modUserProfile::class);
    $userProfile->set('internalKey', $user->get('id'));
    $userProfile->set('fullname', $install->lexicon('default_admin_user'));
    $userProfile->set('email', $settings->get('cmsadminemail'));
    $saved = $userProfile->save();
    if ($saved) {
        /** @var modUserGroupMember $userGroupMembership */
        $userGroupMembership = $modx->newObject(modUserGroupMember::class);
        $userGroupMembership->set('user_group', 1);
        $userGroupMembership->set('member', $user->get('id'));
        $userGroupMembership->set('role', 2);
        $saved = $userGroupMembership->save();

        $user->set('primary_group',1);
        $user->save();
    }
    if ($saved) {
        /** @var modSystemSetting $emailSender */
        $emailSender = $modx->getObject(modSystemSetting::class, array('key' => 'emailsender'));
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
    $settingsFolderPerms = $modx->newObject(modSystemSetting::class);
    $settingsFolderPerms->set('key', 'new_folder_permissions');
    $settingsFolderPerms->set('value', $settings->get('new_folder_permissions'));
    $settingsFolderPerms->save();
}
if ($install->settings->get('new_file_permissions')) {
    /** @var modSystemSetting $settingsFilePerms */
    $settingsFilePerms = $modx->newObject(modSystemSetting::class);
    $settingsFilePerms->set('key', 'new_file_permissions');
    $settingsFilePerms->set('value', $settings->get('new_file_permissions'));
    $settingsFilePerms->save();
}

/* setup load only anonymous ACL*/

/** @var modAccessPolicy $loadOnly */
$loadOnly = $modx->getObject(modAccessPolicy::class,array(
    'name' => 'Load Only',
));
if ($loadOnly) {
    /** @var modAccessContext $access */
    $access= $modx->newObject(modAccessContext::class);
    $access->fromArray(array(
      'target' => 'web',
      'principal_class' => modUserGroup::class,
      'principal' => 0,
      'authority' => 9999,
      'policy' => $loadOnly->get('id'),
    ));
    $access->save();
    unset($access);
}
unset($loadOnly);


/* setup default admin ACLs*/

/** @var modAccessPolicy $adminPolicy */
$adminPolicy = $modx->getObject(modAccessPolicy::class,array(
    'name' => 'Administrator',
));
/** @var modUserGroup $adminGroup */
$adminGroup = $modx->getObject(modUserGroup::class,array(
    'name' => 'Administrator',
));
if ($adminPolicy && $adminGroup) {
    /** @var modAccessContext $access */
    $access= $modx->newObject(modAccessContext::class);
    $access->fromArray(array(
      'target' => 'mgr',
      'principal_class' => modUserGroup::class,
      'principal' => $adminGroup->get('id'),
      'authority' => 0,
      'policy' => $adminPolicy->get('id'),
    ));
    $access->save();
    unset($access);

    $access= $modx->newObject(modAccessContext::class);
    $access->fromArray(array(
      'target' => 'web',
      'principal_class' => modUserGroup::class,
      'principal' => $adminGroup->get('id'),
      'authority' => 0,
      'policy' => $adminPolicy->get('id'),
    ));
    $access->save();
    unset($access);
}
unset($adminPolicy,$adminGroup);

/* add base template and home resource */
$templateContent = file_get_contents(dirname(__DIR__) . '/templates/base_template.tpl');
/** @var modTemplate $template */
$template = $modx->newObject(modTemplate::class);
$template->fromArray(array(
    'templatename' => $install->lexicon('base_template'),
    'content' => $templateContent,
));
if ($template->save()) {

    /** @var modSystemSetting $setting */
    $setting = $modx->getObject(modSystemSetting::class,array(
        'key' => 'default_template',
    ));
    if (!$setting) {
        $setting = $modx->newObject(modSystemSetting::class);
        $setting->fromArray(array(
            'key' => 'default_template',
            'namespace' => 'core',
            'xtype' => 'modx-combo-template',
            'area' => 'site',
        ));
    }
    $setting->set('value', $template->get('id'));
    $setting->save();

    $resourceContent = file_get_contents(dirname(__DIR__) . '/templates/base_resource.tpl');
    /** @var modResource $resource */
    $resource = $modx->newObject(modResource::class);
    $resource->fromArray(array(
        'pagetitle' => $install->lexicon('home'),
        'longtitle' => $install->lexicon('congratulations'),
        'alias' => 'index',
        'contentType' => 'text/html',
        'type' => 'document',
        'published' => true,
        'content' => $resourceContent,
        'template' => $template->get('id'),
        'searchable' => true,
        'cacheable' => true,
        'createdby' => 1,
        'hidemenu' => false,
        'class_key' => modDocument::class,
        'context_key' => 'web',
        'content_type' => 1,
    ));

    if ($resource->save()) {

         /* site_start */
        $setting = $modx->getObject(modSystemSetting::class,array(
            'key' => 'site_start',
        ));
        if (!$setting) {
            $setting = $modx->newObject(modSystemSetting::class);
            $setting->fromArray(array(
                'key' => 'site_start',
                'namespace' => 'core',
                'xtype' => 'textfield',
                'area' => 'site',
            ));
        }
        $setting->set('value', $resource->get('id'));
        $setting->save();

    }

}

/* check for mb extension, set setting accordingly */
$usemb = function_exists('mb_strlen');
if ($usemb) {
    /** @var modSystemSetting $setting */
    $setting = $modx->getObject(modSystemSetting::class,array(
        'key' => 'use_multibyte',
    ));
    if (!$setting) {
        $setting = $modx->newObject(modSystemSetting::class);
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

/* if language != en, set cultureKey to it */
$language = $settings->get('language','en');
if ($language != 'en') {
    // cultureKey
    $setting = $modx->getObject(modSystemSetting::class,array(
        'key' => 'cultureKey',
    ));
    if (!$setting) {
        $setting = $modx->newObject(modSystemSetting::class);
        $setting->fromArray(array(
            'key' => 'cultureKey',
            'namespace' => 'core',
            'xtype' => 'textfield',
            'area' => 'language',
        ));
    }
    $setting->set('value',$language);
    $setting->save();
}

/* add ext_debug setting for sdk distro */
if ('sdk' === trim($currentVersion['distro'], '@')) {
    $setting = $modx->newObject(modSystemSetting::class);
    $setting->fromArray(
        array(
            'key' => 'ext_debug',
            'namespace' => 'core',
            'xtype' => 'combo-boolean',
            'area' => 'system',
            'value' => false
        ),
        '',
        true
    );
    $setting->save();
}

$maxFileSize = ini_get('upload_max_filesize');
$maxFileSize = trim($maxFileSize);
$last = strtolower($maxFileSize[strlen($maxFileSize)-1]);
switch ($last) {
    // The 'G' modifier is available since PHP 5.1.0
    case 'g':
        $maxFileSize *= 1024;
    case 'm':
        $maxFileSize *= 1024;
    case 'k':
        $maxFileSize *= 1024;
}

$settings_maxFileSize = $modx->getObject(modSystemSetting::class, array('key' => 'upload_maxsize'));
if (!$settings_maxFileSize) {
    $settings_maxFileSize = $modx->newObject(modSystemSetting::class);
    $settings_maxFileSize->fromArray(
        array(
            'key' => 'upload_maxsize',
            'namespace' => 'core',
            'area' => 'system',
            'xtype' => 'textfield'
        ),
        '',
        true
    );
}
$settings_maxFileSize->set('value', $maxFileSize);
$settings_maxFileSize->save();

return true;
