<?php
/**
 * Specific upgrades for Revolution 2.0.0-beta-4
 *
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(
    'modAccessActionDom',
    'modActionDom',
);
if (!empty($classes)) {
    $this->createTable($classes);
}
unset($classes);

/* remove old providers */
$c = $this->install->xpdo->newQuery('transport.modTransportProvider');
$c->where(array(
    'service_url' => 'http://wtf.modxcms.com/addons2.js ',
));
$c->orCondition(array(
    'service_url' => 'http://wtf.modxcms.com/repos/addons.js',
));
$providers = $this->install->xpdo->getCollection('transport.modTransportProvider',$c);
foreach ($providers as $provider) { $provider->remove(); }
unset($c,$providers,$provider);

/* remove old security modAction objects */
$c = $this->install->xpdo->newQuery('modAction');
$c->where(array(
    'controller' => 'security/access',
));
$c->orCondition(array(
    'controller' => 'security/access/policy',
));
$actions = $this->install->xpdo->getCollection('modAction',$c);
foreach ($actions as $action) { $action->remove(); }
unset($c,$actions,$action);

/* remove old security modMenu objects */
$c = $this->install->xpdo->newQuery('modMenu');
$c->where(array(
    'text' => 'access_permissions',
));
$c->orCondition(array(
    'text' => 'policy_management',
));
$menus = $this->install->xpdo->getCollection('modMenu',$c);
foreach ($menus as $menu) { $menu->remove(); }
unset($c,$menus,$menu);

/* remove old security policy */
$c = $this->install->xpdo->newQuery('modAccessPolicy');
$c->where(array(
    'name' => 'Context',
));
$policy = $this->install->xpdo->getObject('modAccessPolicy',$c);
if ($policy != null) {
    $policy->set('name','Administrator');
    $policy->save();
}
unset($c,$policy);

/* add modAction indexes */
$class = 'modAction';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE {$table} ADD INDEX `namespace` ( `namespace` )";
$description = $this->install->lexicon('add_index',array('index' => 'namespace','table' => $table));
$this->processResults($class, $description, $sql);
$sql = "ALTER TABLE {$table} ADD INDEX `controller` ( `controller` )";
$description = $this->install->lexicon('add_index',array('index' => 'controller','table' => $table));
$this->processResults($class, $description, $sql);
unset($class,$table,$sql,$description);

/* add modMenu indexes */
$class = 'modMenu';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE {$table} ADD INDEX `parent` ( `parent` )";
$description = $this->install->lexicon('add_index',array('index' => 'parent','table' => $table));
$this->processResults($class, $description, $sql);
$sql = "ALTER TABLE {$table} ADD INDEX `action` ( `action` )";
$description = $this->install->lexicon('add_index',array('index' => 'action','table' => $table));
$this->processResults($class, $description, $sql);
$sql = "ALTER TABLE {$table} ADD INDEX `text` ( `text` )";
$description = $this->install->lexicon('add_index',array('index' => 'text','table' => $table));
$this->processResults($class, $description, $sql);
unset($class,$table,$sql,$description);

/* change modMenu text/parent field */
$class = 'modMenu';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE {$table} CHANGE `parent` `parent` VARCHAR( 100 ) NOT NULL DEFAULT ''";
$description = $this->install->lexicon('change_column',array('old' => 'parent','new' => 'parent varchar','table' => $table));
$this->processResults($class,$description,$sql);

$description = '';
$sql = "ALTER TABLE {$table} DROP PRIMARY KEY, ADD PRIMARY KEY (`text`)";
$this->processResults($class,$description,$sql);
$description = '';
$sql = "ALTER TABLE {$table} CHANGE `text` `text` VARCHAR( 255 ) NOT NULL";
$pkChanged = $this->processResults($class,$description,$sql);
if (!$pkChanged) {
    $description = '';
    $sql = "ALTER TABLE {$table} ADD PRIMARY KEY (`text`)";
    $this->processResults($class,$description,$sql);
}
$sql = "DROP INDEX `text` ON {$table}";
$description = $this->install->lexicon('drop_index',array('index' => 'text','table' => $table));
$this->processResults($class, $description, $sql);
unset($class,$description,$sql,$table);

/* fix 3PC component menus */
$c = $this->install->xpdo->newQuery('modMenu');
$c->where(array(
    'parent' => 2,
));
$menus = $this->install->xpdo->getCollection('modMenu',$c);
foreach ($menus as $menu) {
    $menu->set('parent','components');
    $menu->save();
}
unset($c,$menus,$menu);

/* add active field to modUser */
$class = 'modUser';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'active','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' AFTER `class_key`";
$this->processResults($class,$description,$sql);