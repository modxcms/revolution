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
$description = sprintf($this->install->lexicon['add_index'],'namespace',$table);
$this->processResults($class, $description, $sql);
$sql = "ALTER TABLE {$table} ADD INDEX `controller` ( `controller` )";
$description = sprintf($this->install->lexicon['add_index'],'controller',$table);
$this->processResults($class, $description, $sql);
unset($class,$table,$sql,$description);

/* add modMenu indexes */
$class = 'modMenu';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE {$table} ADD INDEX `parent` ( `parent` )";
$description = sprintf($this->install->lexicon['add_index'],'parent',$table);
$this->processResults($class, $description, $sql);
$sql = "ALTER TABLE {$table} ADD INDEX `action` ( `action` )";
$description = sprintf($this->install->lexicon['add_index'],'action',$table);
$this->processResults($class, $description, $sql);
$sql = "ALTER TABLE {$table} ADD INDEX `text` ( `text` )";
$description = sprintf($this->install->lexicon['add_index'],'text',$table);
$this->processResults($class, $description, $sql);
unset($class,$table,$sql,$description);

/* change modMenu text/parent field */
$class = 'modMenu';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE {$table} CHANGE `parent` `parent` VARCHAR( 100 ) NOT NULL DEFAULT ''";
$description = sprintf($this->install->lexicon['change_column'],'parent','parent varchar',$table);
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
$description = sprintf($this->install->lexicon['drop_index'],'text',$table);
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
