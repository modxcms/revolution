<?php
/**
 * Specific upgrades for Revolution 2.0.5
 *
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(
    'modAccessPolicyTemplate',
    'modAccessPolicyTemplateGroup',
    'modFormCustomizationProfile',
    'modFormCustomizationProfileUserGroup',
    'modFormCustomizationSet',
    'modActionField',
);
if (!empty($classes)) {
    $this->createTable($classes);
}
unset($classes);

/* add rank field to FC rules */
$class = 'modActionDom';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'rank','table' => $table));
$sql = "ALTER TABLE {$table} ADD `rank` INT(11) NULL DEFAULT '0' AFTER `for_parent`";
$this->processResults($class,$description,$sql);


/* adjust user comment field to be more expansive (#2614) */
$class = 'modUserProfile';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('change_column',array(
    'old' => 'comment VARCHAR(255)',
    'new' => 'comment TEXT',
    'table' => $table,
));
$sql = "ALTER TABLE {$table} CHANGE `comment` `comment` TEXT NOT NULL DEFAULT ''";
$this->processResults($class,$description,$sql);

/* add template field to modAccessPolicy */
$class = 'modAccessPolicy';
$table = $modx->getTableName($class);
$sql = "ALTER TABLE {$table} ADD `template` INT(11) NULL DEFAULT '0' AFTER `parent`";
$modx->exec($sql);

/* add template field to modAccessPermission */
$class = 'modAccessPermission';
$table = $modx->getTableName($class);
$sql = "ALTER TABLE {$table} ADD `template` INT(11) NULL DEFAULT '0' AFTER `id`";
$modx->exec($sql);

/* add template index to modAccessPermission */
$sql = "ALTER TABLE {$table} ADD INDEX `template` (`template`)";
$modx->exec($sql);

/* add template index to modAccessPolicy */
$sql = "ALTER TABLE {$table} ADD INDEX `template` (`template`)";
$modx->exec($sql);

/* Rename Administrator modAccessPolicyTemplateGroup to Admin */
$aptg = $modx->getObject('modAccessPolicyTemplateGroup',array('name' => 'Administrator'));
if ($aptg) {
    $aptg->set('name','Admin');
    $aptg->save();
}

/* Rename Access Policy Templates to postfix Template for clarity [#2695] */
$names = array('Administrator','Resource','Element','Object');
foreach ($names as $name) {
    $pt = $modx->getObject('modAccessPolicyTemplate',array('name' => $name));
    if ($pt) {
        $pt->set('name',$pt->get('name').'Template');
        $pt->save();
    }
}

/* upgrade extension_packages setting to JSON */
$setting = $modx->getObject('modSystemSetting',array('key' => 'extension_packages'));
if (!$setting) {
    $setting = $modx->newObject('modSystemSetting');
    $setting->set('key','extension_packages');
    $setting->set('value','{}');
    $setting->set('xtype','textfield');
    $setting->set('area','system');
    $setting->set('namespace','core');
    $setting->save();
} else {
    $v = $setting->get('value');
    if (strpos($v,'}') === false) {
        $ep = array();
        $pkgs = explode(',',$v);
        foreach ($pkgs as $pkg) {
            $dt = explode(':',$pkg,2);
            if (!empty($dt) && !empty($dt[1])) {
                $ep[$dt[0]] = array(
                    'path' => $dt[1],
                );
            }
        }
        $setting->set('value',$modx->toJSON($ep));
        $setting->save();
    }
}

/* [#2592] prevent *cache.php from being in system setting upload_files */
$setting = $modx->getObject('modSystemSetting',array('key' => 'upload_files'));
if ($setting) {
    $v = $setting->get('value');
    $v = str_replace('cache,','',$v);
    $setting->set('value',$v);
    $setting->save();
}


/* add set field/index to modActionDom */
$class = 'modActionDom';
$table = $modx->getTableName($class);
$sql = "ALTER TABLE {$table} ADD `set` INT(11) NULL DEFAULT '0' AFTER `id`";
$modx->exec($sql);
$sql = "ALTER TABLE {$table} ADD INDEX `set` (`set`)";
$modx->exec($sql);
