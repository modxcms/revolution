<?php
/**
 * Upgrade-specific scripts
 */
/* handle change of manager_theme to default (FIXME: temp hack) */
if ($this->settings->get('installmode') == modInstall::MODE_UPGRADE_EVO) {
    $managerTheme = $this->xpdo->getObject('modSystemSetting', array(
        'key' => 'manager_theme',
        'value:!=' => 'default'
    ));
    if ($managerTheme) {
        $managerTheme->set('value', 'default');
        $managerTheme->save();
    }
    unset($managerTheme);
}

/* handle change of default language to proper IANA code based on initial language selection in setup */
$managerLanguage = $this->xpdo->getObject('modSystemSetting', array(
    'key' => 'manager_language',
));
if ($managerLanguage) {
    $language = $this->settings->get('language');
    $managerLanguage->set('value',!empty($language) ? $language : 'en');
    $managerLanguage->save();
}
unset($managerLanguage);

/* update settings_version */
$settings_version = $this->xpdo->getObject('modSystemSetting', array(
    'key' => 'settings_version',
));
if ($settings_version == null) {
    $settings_version = $this->xpdo->newObject('modSystemSetting');
    $settings_version->set('key','settings_version');
    $settings_version->set('xtype','textfield');
    $settings_version->set('namespace','core');
    $settings_version->set('area','system');
}
$currentVersion = include MODX_CORE_PATH . 'docs/version.inc.php';
$settings_version->set('value', $currentVersion['full_version']);
$settings_version->save();

/* make sure admin user (1) has proper group and role */
$adminUser = $this->xpdo->getObject('modUser', 1);
if ($adminUser) {
    $userGroupMembership = $this->xpdo->getObject('modUserGroupMember', array('user_group' => true, 'member' => true));
    if (!$userGroupMembership) {
        $userGroupMembership = $this->xpdo->newObject('modUserGroupMember');
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
$adminPolicy = $this->xpdo->getObject('modAccessPolicy',array(
    'name' => 'Administrator',
));
$adminGroup = $this->xpdo->getObject('modUserGroup',array(
    'name' => 'Administrator',
));
if ($adminPolicy && $adminGroup) {
    $access= $this->xpdo->getObject('modAccessContext',array(
        'target' => 'mgr',
        'principal_class' => 'modUserGroup',
        'principal' => $adminGroup->get('id'),
        'authority' => 0,
        'policy' => $adminPolicy->get('id'),
    ));
    if (!$access) {
        $access = $this->xpdo->newObject('modAccessContext');
        $access->fromArray(array(
          'target' => 'mgr',
          'principal_class' => 'modUserGroup',
          'principal' => $adminGroup->get('id'),
          'authority' => 0,
          'policy' => $adminPolicy->get('id'),
        ));
        $access->save();
    }
    unset($access);

    $access = $this->xpdo->getObject('modAccessContext',array(
      'target' => 'web',
      'principal_class' => 'modUserGroup',
      'principal' => $adminGroup->get('id'),
      'authority' => 0,
      'policy' => $adminPolicy->get('id'),
    ));
    if (!$access) {
        $access= $this->xpdo->newObject('modAccessContext');
        $access->fromArray(array(
          'target' => 'web',
          'principal_class' => 'modUserGroup',
          'principal' => $adminGroup->get('id'),
          'authority' => 0,
          'policy' => $adminPolicy->get('id'),
        ));
        $access->save();
    }
    unset($access);
}
unset($adminPolicy,$adminGroup);

$language = $this->settings->get('language','en');
if ($language != 'en') {
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

/* set welcome screen URL */
$setting = $this->xpdo->getObject('modSystemSetting',array(
    'key' => 'welcome_screen_url',
));
if (!$setting) {
    $setting = $this->xpdo->newObject('modSystemSetting');
    $setting->fromArray(array(
        'key' => 'welcome_screen_url',
        'namespace' => 'core',
        'xtype' => 'textfield',
        'area' => 'manager',
    ));
}
$setting->set('value','http://misc.modx.com/revolution/welcome.20.html');
$setting->save();

/* Access Policy changes (have to happen post package install) */

/* setup a setting to run this only once */
$setting = $modx->getObject('modSystemSetting',array(
    'key' => 'access_policies_version',
    'value' => '1.0',
));
if (!$setting) {
    /* truncate permissions in modAccessPermission and migrate to modAccessPolicyTemplate objects from modAccessPolicy.data
     * first get the standard policies, and then array_diff with Admin policy and unknown policies
     * if an unknown policy doesnt contain any new permissions that arent in Admin policy,
     * just switch it to the Admin Policy Template. Otherwise, create a new AP template
     * based on the Policy's name (first look for an existing one).
     */
    /* get admin policy and list of standard policies */
    $standards = array('Administrator','Resource','Load Only','Load, List and View','Object','Element');
    $adminPolicy = $modx->getObject('modAccessPolicy',array('name' => 'Administrator'));
    $adminPolicyData = $adminPolicy ? $adminPolicy->get('data') : array();

    $adminPolicyTpl = $modx->getObject('modAccessPolicyTemplate',array('name' => 'Administrator'));
    if (!$adminPolicyTpl) {
        $modx->log(XPDO::LOG_LEVEL_ERROR,'Could not find Administrator Access Policy Template');
    }
    $adminPolicyTplGroup = $adminPolicyTpl ? $adminPolicyTpl->get('template_group') : 1;

    /* get all existing policies */
    $c = $modx->newQuery('modAccessPolicy');
    $c->sortby('name','ASC');
    $policies = $modx->getCollection('modAccessPolicy',$c);

    foreach ($policies as $policy) {
        /* standard policies */
        if (in_array($policy->get('name'),$standards)) {
            if ($policy->get('template') != 0) continue;

            $id = $adminPolicyTpl ? $adminPolicyTpl->get('id') : 3; /* default to object */
            switch ($policy->get('name')) {
                case 'Resource':
                    $policyTpl = $modx->getObject('modAccessPolicyTemplate',array('name' => 'Resource'));
                    if ($policyTpl) {
                        $id = $policyTpl->get('id');
                    } else {
                        $modx->log(XPDO::LOG_LEVEL_ERROR,'Could not find Resource Access Policy Template');
                    }
                    break;
                case 'Element':
                    $policyTpl = $modx->getObject('modAccessPolicyTemplate',array('name' => 'Element'));
                    if ($policyTpl) {
                        $id = $policyTpl->get('id');
                    } else {
                        $modx->log(XPDO::LOG_LEVEL_ERROR,'Could not find Element Access Policy Template');
                    }
                    break;
                case 'Object':
                case 'Load, List and View':
                case 'Load Only':
                    $policyTpl = $modx->getObject('modAccessPolicyTemplate',array('name' => 'Object'));
                    if ($policyTpl) {
                        $id = $policyTpl->get('id');
                    } else {
                        $modx->log(XPDO::LOG_LEVEL_ERROR,'Could not find Object Access Policy Template');
                    }
                    break;
                case 'Administrator':
                default:
                    break;
            }
            $modx->log(xPDO::LOG_LEVEL_DEBUG,'Setting template to '.$id.' for standard '.$policy->get('name'));

            /* prevent duplicate standard policies */
            $policyExists = $modx->getObject('modAccessPolicy',array(
                'template' => $id,
                'name' => $policy->get('name'),
            ));
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
            $policyTpl = $modx->getObject('modAccessPolicyTemplate',array(
                'name' => $policy->get('name'),
            ));
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
                    $policyTpl = $modx->newObject('modAccessPolicyTemplate');
                    $policyTpl->fromArray(array(
                        'name' => $policy->get('name'),
                        'template_group' => $adminPolicyTplGroup,
                        'description' => $policy->get('description'),
                    ));
                    $lexicon = $policy->get('lexicon');
                    if (!empty($lexicon)) {
                        $modx->log(xPDO::LOG_LEVEL_DEBUG,'Setting lexicon to '.$lexicon.' for policy '.$policy->get('name'));
                        $policyTpl->set('lexicon',$lexicon);
                    }
                    $policyTpl->save();
                    $modx->log(xPDO::LOG_LEVEL_DEBUG,'Setting template to '.$policyTpl->get('id').' for '.$policy->get('name'));
                    $policy->set('template',$policyTpl->get('id'));
                    $policy->save();

                    $permissions = $modx->getCollection('modAccessPermission',array(
                        'policy' => $policy->get('id'),
                    ));
                    // add permissions to tpl
                    foreach ($permissions as $permission) {
                        // prevent duplicate permissions
                        $permExists = $modx->getObject('modAccessPermission',array(
                            'name' => $permission->get('name'),
                            'template' => $policyTpl->get('id'),
                        ));
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
    $permissions =$modx->getCollection('modAccessPermission',array('template' => 0));
    foreach ($permissions as $permission) { $permission->remove(); }
    unset($permissions,$permission);


    /* drop policy index from modAccessPermission */
    $class = 'modAccessPermission';
    $table = $modx->getTableName($class);
    $sql = "ALTER TABLE {$table} DROP INDEX `policy`";
    $modx->exec($sql);

    /* drop policy field from modAccessPermission */
    $sql = "ALTER TABLE {$table} DROP COLUMN `policy`";
    $modx->exec($sql);

    /* add setting so that this runs only once to prevent errors or goof-ups */
    $setting = $modx->newObject('modSystemSetting');
    $setting->set('key','access_policies_version');
    $setting->set('namespace','core');
    $setting->set('area','system');
    $setting->set('value','1.0');
    $setting->save();
}

return true;