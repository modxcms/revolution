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

/* get version information */
$currentVersion = include MODX_CORE_PATH . 'docs/version.inc.php';

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
$settings_version->set('value', $currentVersion['full_version']);
$settings_version->save();

/* update settings_distro */
$settings_distro = $this->xpdo->getObject('modSystemSetting', array(
    'key' => 'settings_distro',
));
if ($settings_distro == null) {
    $settings_distro = $this->xpdo->newObject('modSystemSetting');
    $settings_distro->set('key','settings_distro');
    $settings_distro->set('xtype','textfield');
    $settings_distro->set('namespace','core');
    $settings_distro->set('area','system');
}
$settings_distro->set('value', trim($currentVersion['distro'], '@'));
$settings_distro->save();

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

    $adminPolicyTpl = $modx->getObject('modAccessPolicyTemplate',array('name' => 'AdministratorTemplate'));
    if (!$adminPolicyTpl) {
        $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find Administrator Access Policy Template');
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
                    $policyTpl = $modx->getObject('modAccessPolicyTemplate',array('name' => 'ResourceTemplate'));
                    if ($policyTpl) {
                        $id = $policyTpl->get('id');
                    } else {
                        $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find Resource Access Policy Template');
                    }
                    break;
                case 'Element':
                    $policyTpl = $modx->getObject('modAccessPolicyTemplate',array('name' => 'ElementTemplate'));
                    if ($policyTpl) {
                        $id = $policyTpl->get('id');
                    } else {
                        $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find Element Access Policy Template');
                    }
                    break;
                case 'Object':
                case 'Load, List and View':
                case 'Load Only':
                    $policyTpl = $modx->getObject('modAccessPolicyTemplate',array('name' => 'ObjectTemplate'));
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
                        'name' => $policy->get('name').'Template',
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

/* upgrade FC rules */
$ct = $modx->getCount('modFormCustomizationProfile');
if (empty($ct) || $modx->getOption('fc_upgrade_100',null,false)) {
    $c = $modx->newQuery('modActionDom');
    $c->innerJoin('modAction','Action');
    $c->leftJoin('modAccessActionDom','Access','Access.target = modActionDom.id');
    $c->where(array(
        'modActionDom.active' => true,
    ));
    $c->select(array(
        'modActionDom.*',
        'Action.controller',
        'Access.principal',
    ));
    $c->sortby('Access.principal','ASC');
    $c->sortby('modActionDom.action','ASC');
    $c->sortby('modActionDom.constraint_field','ASC');
    $rules = $modx->getCollection('modActionDom',$c);

    $currentProfile = 0;
    $currentAction = 0;
    $currentSet = 0;
    $currentConstraintField = '';
    $currentConstraintValue = '';
    $usergroup = false;
    foreach ($rules as $rule) {
        $newSet = false;

        /* if a new usergroup, assign a new profile */
        $principal = $rule->get('principal');
        if (!isset($currentUserGroup) || $currentUserGroup != $principal) {
            if (!empty($principal)) {
                $usergroup = $modx->getObject('modUserGroup',$principal);
                if (!$usergroup) {
                    $currentUserGroup = 0;
                } else {
                    $currentUserGroup = $usergroup->get('id');
                }
            } else { /* if no usergroup */
                $usergroup = false;
                $currentUserGroup = 0;
            }

            $profile = $modx->newObject('modFormCustomizationProfile');
            if ($usergroup) {
                $profile->set('name','Default: '.$usergroup->get('name'));
                $profile->set('description','Default profile based on import from Revolution upgrade for '.$usergroup->get('name').' User Group.');
            } else {
                $profile->set('name','Default');
                $profile->set('description','Default profile based on import from Revolution upgrade.');
            }
            $profile->set('active',true);
            if (!$profile->save()) {
                $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not create modFormCustomizationProfile object: '.print_r($profile->toArray(),true));
            }
            $currentProfile = $profile->get('id');

            /* create user group record */
            if ($usergroup) {
                $fcpug = $modx->newObject('modFormCustomizationProfileUserGroup');
                $fcpug->set('usergroup',$usergroup->get('id'));
                $fcpug->set('profile',$profile->get('id'));
                if (!$fcpug->save()) {
                    $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not associate Profile to User Group: '.print_r($fcpug->toArray(),true));
                }
            }
            $newSet = true;
        } else {
            $modx->log(xPDO::LOG_LEVEL_DEBUG,'Skipping Profile creation, already has one for this rule.');
        }

        /* if moving to a new action, create a new set */
        if ($currentAction != $rule->get('action')) {
            $currentAction = $rule->get('action');
            $newSet = true;
        }
        /* if constraint is different, create a new set */
        if ($currentConstraintField != $rule->get('constraint_field')) {
            $currentConstraintField = $rule->get('constraint_field');
            $newSet = true;
        }
        if ($currentConstraintValue != $rule->get('constraint')) {
            $currentConstraintValue = $rule->get('constraint');
            $newSet = true;
        }

        /* if generating a new set */
        if ($newSet) {
            $set = $modx->newObject('modFormCustomizationSet');
            $set->set('profile',$currentProfile);
            $set->set('action',$rule->get('action'));
            $set->set('active',true);

            /* set Set template if constraint is template */
            if ($rule->get('constraint_field') == 'template') {
                $set->set('template',$rule->get('constraint'));
            } else {
                /* otherwise a non-template field, set as constraint */
                $set->set('constraint',$rule->get('constraint'));
                $set->set('constraint_field',$rule->get('constraint_field'));
                $set->set('constraint_class','modResource');
            }
            if (!$set->save()) {
                $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not save new Set: '.print_r($set->toArray(),true));
            }
            $currentSet = $set->get('id');
        }
        $rule->set('set',$currentSet);

        /* if constraint is a template, erase since it is now in set */
        if ($rule->get('constraint_field') == 'template') {
            $rule->set('constraint','');
            $rule->set('constraint_field','');
            $rule->set('constraint_class','');
        }
        /* if action is resource/create, set rule to for_parent */
        if ($rule->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }

        /* flip tvMove name/value */
        if ($rule->get('rule') == 'tvMove') {
            $name = $rule->get('name');
            $rule->set('name',$rule->get('value'));
            $rule->set('value',$name);
        }
        if (!$rule->save()) {
            $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not save new modActionDom rule: '.print_r($rule->toArray(),true));
        }

        /* explode csv fields into new rules */
        $field = $rule->get('name');
        $fields = explode(',',$field);
        if (count($fields) > 1) {
            $idx = 0;
            foreach ($fields as $fld) {
                if ($idx != 0) {
                    /* create new rule from other fields */
                    $newRule = $modx->newObject('modActionDom');
                    $newRule->fromArray($rule->toArray());
                    $newRule->set('name',$fld);
                    $newRule->save();
                } else {
                    /* save orig rule to first field */
                    $rule->set('name',$fld);
                    $rule->save();
                }
                $idx++;
            }
        }
    }

    /* remove all inactive rules */
    $rules = $modx->getCollection('modActionDom',array('active' => false));
    foreach ($rules as $rule) { $rule->remove(); }

    /* remove all non-resource rules */
    $c = $modx->newQuery('modActionDom');
    $c->innerJoin('modAction','Action');
    $c->where(array(
        'Action.controller:!=' => 'resource/create',
        'AND:Action.controller:!=' => 'resource/update',
    ));
    $invalidRules = $modx->getCollection('modActionDom',$c);
    foreach ($invalidRules as $invalidRule) {
        $invalidRule->remove();
    }
}

return true;
