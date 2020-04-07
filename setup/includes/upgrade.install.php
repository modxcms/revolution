<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Upgrade-specific scripts
 *
 * @var modInstallRunner   $this
 * @var modInstall         $install
 * @var xPDO               $modx
 * @var modInstallSettings $settings
 *
 * @package modx
 * @subpackage setup
 */
/* handle change of manager_theme to default (FIXME: temp hack) */

use MODX\Revolution\modAccessActionDom;
use MODX\Revolution\modAccessContext;
use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modAccessPolicyTemplate;
use MODX\Revolution\modAction;
use MODX\Revolution\modActionDom;
use MODX\Revolution\modFormCustomizationProfile;
use MODX\Revolution\modFormCustomizationProfileUserGroup;
use MODX\Revolution\modFormCustomizationSet;
use MODX\Revolution\modResource;
use MODX\Revolution\modSystemSetting;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\Transport\modTransportPackage;
use MODX\Revolution\Transport\modTransportProvider;
use xPDO\xPDO;

if ($settings->get('installmode') == modInstall::MODE_UPGRADE_EVO) {
    $managerTheme = $modx->getObject(modSystemSetting::class, [
        'key' => 'manager_theme',
        'value:!=' => 'default'
    ]);
    if ($managerTheme) {
        $managerTheme->set('value', 'default');
        $managerTheme->save();
    }
    unset($managerTheme);
}

/* handle change of default language to proper IANA code based on initial language selection in setup */
$managerLanguage = $modx->getObject(modSystemSetting::class, [
    'key' => 'manager_language',
]);
if ($managerLanguage) {
    $managerLanguage->remove();
}
unset($managerLanguage);

/* get version information */
$currentVersion = include MODX_CORE_PATH . 'docs/version.inc.php';

/* update settings_version */
$settings_version = $modx->getObject(modSystemSetting::class, [
    'key' => 'settings_version',
]);
if ($settings_version == null) {
    $settings_version = $modx->newObject(modSystemSetting::class);
    $settings_version->set('key','settings_version');
    $settings_version->set('xtype','textfield');
    $settings_version->set('namespace','core');
    $settings_version->set('area','system');
}
$settings_version->set('value', $currentVersion['full_version']);
$settings_version->save();

/* update settings_distro */
$settings_distro = $modx->getObject(modSystemSetting::class, [
    'key' => 'settings_distro',
]);
if ($settings_distro == null) {
    $settings_distro = $modx->newObject(modSystemSetting::class);
    $settings_distro->set('key','settings_distro');
    $settings_distro->set('xtype','textfield');
    $settings_distro->set('namespace','core');
    $settings_distro->set('area','system');
}
$settings_distro->set('value', trim($currentVersion['distro'], '@'));
$settings_distro->save();

/* make sure admin user (1) has proper group and role */
$adminUser = $modx->getObject(modUser::class, 1);
if ($adminUser) {
    $userGroupMembership = $modx->getObject(modUserGroupMember::class, ['user_group' => true, 'member' => true]);
    if (!$userGroupMembership) {
        $userGroupMembership = $modx->newObject(modUserGroupMember::class);
        $userGroupMembership->set('user_group', 1);
        $userGroupMembership->set('member', 1);
        $userGroupMembership->set('role', 2);
        $userGroupMembership->save();
    } else {
        $userGroupMembership->set('role', 2);
        $userGroupMembership->save();
    }
}

/* setup default admin ACLs */
$adminPolicy = $modx->getObject(modAccessPolicy::class, [
    'name' => 'Administrator',
]);
$adminGroup = $modx->getObject(modUserGroup::class, [
    'name' => 'Administrator',
]);
if ($adminPolicy && $adminGroup) {
    $access= $modx->getObject(modAccessContext::class, [
        'target' => 'mgr',
        'principal_class' => modUserGroup::class,
        'principal' => $adminGroup->get('id'),
        'authority' => 0,
        'policy' => $adminPolicy->get('id'),
    ]);
    if (!$access) {
        $access = $modx->newObject(modAccessContext::class);
        $access->fromArray([
          'target' => 'mgr',
          'principal_class' => modUserGroup::class,
          'principal' => $adminGroup->get('id'),
          'authority' => 0,
          'policy' => $adminPolicy->get('id'),
        ]);
        $access->save();
    }
    unset($access);

    $access = $modx->getObject(modAccessContext::class, [
      'target' => 'web',
      'principal_class' => modUserGroup::class,
      'principal' => $adminGroup->get('id'),
      'authority' => 0,
      'policy' => $adminPolicy->get('id'),
    ]);
    if (!$access) {
        $access= $modx->newObject(modAccessContext::class);
        $access->fromArray([
          'target' => 'web',
          'principal_class' => modUserGroup::class,
          'principal' => $adminGroup->get('id'),
          'authority' => 0,
          'policy' => $adminPolicy->get('id'),
        ]);
        $access->save();
    }
    unset($access);
}
unset($adminPolicy,$adminGroup);

/* Access Policy changes (have to happen post package install) */

/* setup a setting to run this only once */
$setting = $modx->getObject(modSystemSetting::class, [
    'key' => 'access_policies_version',
    'value' => '1.0',
]);
if (!$setting) {
    /* truncate permissions in modAccessPermission and migrate to modAccessPolicyTemplate objects from modAccessPolicy.data
     * first get the standard policies, and then array_diff with Admin policy and unknown policies
     * if an unknown policy doesnt contain any new permissions that arent in Admin policy,
     * just switch it to the Admin Policy Template. Otherwise, create a new AP template
     * based on the Policy's name (first look for an existing one).
     */
    /* get admin policy and list of standard policies */
    $standards = ['Administrator','Resource','Load Only','Load, List and View','Object','Element'];
    $adminPolicy = $modx->getObject(modAccessPolicy::class, ['name' => 'Administrator']);
    $adminPolicyData = $adminPolicy ? $adminPolicy->get('data') : [];

    $adminPolicyTpl = $modx->getObject(modAccessPolicyTemplate::class, ['name' => 'AdministratorTemplate']);
    if (!$adminPolicyTpl) {
        $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find Administrator Access Policy Template');
    }
    $adminPolicyTplGroup = $adminPolicyTpl ? $adminPolicyTpl->get('template_group') : 1;

    /* get all existing policies */
    $c = $modx->newQuery(modAccessPolicy::class);
    $c->sortby('name','ASC');
    $policies = $modx->getCollection(modAccessPolicy::class, $c);

    /** @var modAccessPolicy $policy */
    foreach ($policies as $policy) {
        /* standard policies */
        if (in_array($policy->get('name'),$standards)) {
            if ($policy->get('template') != 0) continue;

            $id = $adminPolicyTpl ? $adminPolicyTpl->get('id') : 3; /* default to object */
            switch ($policy->get('name')) {
                case 'Resource':
                    $policyTpl = $modx->getObject(modAccessPolicyTemplate::class, ['name' => 'ResourceTemplate']);
                    if ($policyTpl) {
                        $id = $policyTpl->get('id');
                    } else {
                        $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find Resource Access Policy Template');
                    }
                    break;
                case 'Element':
                    $policyTpl = $modx->getObject(modAccessPolicyTemplate::class, ['name' => 'ElementTemplate']);
                    if ($policyTpl) {
                        $id = $policyTpl->get('id');
                    } else {
                        $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find Element Access Policy Template');
                    }
                    break;
                case 'Object':
                case 'Load, List and View':
                case 'Load Only':
                    $policyTpl = $modx->getObject(modAccessPolicyTemplate::class, ['name' => 'ObjectTemplate']);
                    if ($policyTpl) {
                        $id = $policyTpl->get('id');
                    } else {
                        $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find Object Access Policy Template');
                    }
                    break;
                case 'Administrator':
                default:
                    break;
            }
            $modx->log(xPDO::LOG_LEVEL_DEBUG,'Setting template to '.$id.' for standard '.$policy->get('name'));

            /* prevent duplicate standard policies */
            $policyExists = $modx->getObject(modAccessPolicy::class, [
                'template' => $id,
                'name' => $policy->get('name'),
            ]);
            if ($policyExists) {
                $policy->remove();
            } else {
                $policy->set('template',$id);
                $policy->save();
            }
            unset($policyTpl,$policy,$id,$policyExists);

        } else {
            $modx->log(xPDO::LOG_LEVEL_DEBUG,'Found non-standard policy: '.$policy->get('name'));
            /* non-standard policies */
            if (!$policyTpl = $policy->getOne('Template')) {
                $policyTpl = $modx->getObject(modAccessPolicyTemplate::class, [
                    'name' => $policy->get('name'),
                ]);
            }
            if (!$policyTpl) {
                /* array_diff data with standard admin policy */
                $data = $policy->get('data');
                $diff = array_diff_key($data,$adminPolicyData);
                $modx->log(xPDO::LOG_LEVEL_DEBUG,'Diff: '.print_r($diff,true));

                /* if the unknown policy has all the perms and no new perms of the admin
                 * policy, just set its tpl to the admin policy tpl
                 */
                if (empty($diff) && $adminPolicyTpl) {
                    $policy->set('template',$adminPolicyTpl->get('id'));
                    $policy->save();

                /* otherwise create a custom policy tpl */
                } else {
                    $policyTpl = $modx->newObject(modAccessPolicyTemplate::class);
                    $policyTpl->fromArray([
                        'name' => $policy->get('name').'Template',
                        'template_group' => $adminPolicyTplGroup,
                        'description' => $policy->get('description'),
                    ]);
                    $lexicon = $policy->get('lexicon');
                    if (!empty($lexicon)) {
                        $modx->log(xPDO::LOG_LEVEL_DEBUG,'Setting lexicon to '.$lexicon.' for policy '.$policy->get('name'));
                        $policyTpl->set('lexicon',$lexicon);
                    }
                    $policyTpl->save();
                    $modx->log(xPDO::LOG_LEVEL_DEBUG,'Setting template to '.$policyTpl->get('id').' for '.$policy->get('name'));
                    $policy->set('template',$policyTpl->get('id'));
                    $policy->save();

                    $permissions = $modx->getCollection(modAccessPermission::class, [
                        'policy' => $policy->get('id'),
                    ]);
                    // add permissions to tpl
                    foreach ($permissions as $permission) {
                        // prevent duplicate permissions
                        /** @var modAccessPermission $permission */
                        $permExists = $modx->getObject(modAccessPermission::class, [
                            'name' => $permission->get('name'),
                            'template' => $policyTpl->get('id'),
                        ]);
                        if ($permExists) {
                            $permission->remove();
                        } else {
                            $permission->set('template',$policyTpl->get('id'));
                            $permission->save();
                        }
                    }

                }
            }
        }
    }
    unset($policy,$permission,$permissions,$policies,$policy,$policyTpl,$adminPolicy,$adminPolicyData,$adminPolicyTpl,$adminPolicyTplGroup,$data);
    /* now remove all 0 template permissions */
    $permissions =$modx->getCollection(modAccessPermission::class, ['template' => 0]);
    foreach ($permissions as $permission) { $permission->remove(); }
    unset($permissions,$permission);


    /* drop policy index from modAccessPermission */
    $class = modAccessPermission::class;
    $table = $modx->getTableName($class);
    $sql = "ALTER TABLE {$table} DROP INDEX policy";
    $modx->exec($sql);

    /* drop policy field from modAccessPermission */
    $sql = "ALTER TABLE {$table} DROP COLUMN policy";
    $modx->exec($sql);

    /* add setting so that this runs only once to prevent errors or goof-ups */
    $setting = $modx->newObject(modSystemSetting::class);
    $setting->set('key','access_policies_version');
    $setting->set('namespace','core');
    $setting->set('area','system');
    $setting->set('value','1.0');
    $setting->save();
}

/* remove modxcms.com provider if it occurs */
$provider = $modx->getObject(modTransportProvider::class, [
    'service_url' => 'http://rest.modxcms.com/extras/',
]);
$newProvider = $modx->getObject(modTransportProvider::class, [
    'service_url' => 'https://rest.modx.com/extras/',
]);
if ($provider && $newProvider && $provider->get('id') != $newProvider->get('id')) {
    /* if 2 providers found, remove old one */
    if ($provider->remove()) {
        /* and then migrate old packages to new provider */
        $packages = $modx->getCollection(modTransportPackage::class, [
            'provider' => $provider->get('id'),
        ]);
        foreach ($packages as $package) {
            /** @var modTransportPackage $package */
            $package->set('provider',$newProvider->get('id'));
            $package->save();
        }
    }
} else if ($provider && empty($newProvider)) {
    $provider->set('service_url','https://rest.modx.com/extras/');
    $provider->save();
}

/* Set session_gc_maxlifetime equal to session_cookie_lifetime or session.gc_maxlifetime if empty */
$setting = $modx->getObject(modSystemSetting::class, ['key' => 'session_gc_maxlifetime']);
if ($setting && $setting->get('value') == '') {
    $session_gc_maxlifetime = (integer) $modx->getOption('session_cookie_lifetime', null, @ini_get('session.gc_maxlifetime'));
    if ($session_gc_maxlifetime < 1) {
        $session_gc_maxlifetime = 604800;
    }
    $setting->set('value', $session_gc_maxlifetime);
    $setting->save();
}
unset($setting);

/* add ext_debug setting for sdk distro and turn it off if it exists outside sdk */
$setting = $modx->getObject(modSystemSetting::class, ['key' => 'ext_debug']);
if (!$setting && ('sdk' === trim($currentVersion['distro'], '@') || 'git' === trim($currentVersion['distro'], '@'))) {
    $setting = $modx->newObject(modSystemSetting::class);
    $setting->fromArray(
        [
            'key' => 'ext_debug',
            'namespace' => 'core',
            'xtype' => 'combo-boolean',
            'area' => 'system',
            'value' => false
        ],
        '',
        true
    );
    $setting->save();
} elseif ($setting &&  !in_array(trim($currentVersion['distro'], '@'), ['sdk', 'git'])) {
    $setting->set('value', false);
    $setting->save();
}

return true;
