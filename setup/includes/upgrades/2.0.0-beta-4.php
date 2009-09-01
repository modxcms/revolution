<?php
/**
 * Specific upgrades for Revolution 2.0.0-beta-4
 *
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(
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