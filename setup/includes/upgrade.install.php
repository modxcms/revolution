<?php
/* handle change of manager_theme to default (FIXME: temp hack) */
$managerTheme = $this->xpdo->getObject('modSystemSetting', array(
    'key' => 'manager_theme',
    'value:!=' => 'default'
));
if ($managerTheme) {
    $managerTheme->set('value', 'default');
    $managerTheme->save();
}
unset($managerTheme);

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

return true;