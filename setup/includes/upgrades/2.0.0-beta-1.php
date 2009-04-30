<?php
/**
 * Specific upgrades for Revolution 2.0.0-beta-1
 *
 * @package setup
 */
/* handle new class creation */
$classes = array(
    'modElementPropertySet',
    'modPropertySet'
);
if (!empty($classes)) {
    $this->createTable($classes);
}
unset($classes);

/* remove some menu items that are deprecated from alpha */
/* first, remove the dashes from revo-alpha */
$dashes = $this->install->xpdo->getCollection('modMenu',array( 'text' => '-'));
foreach ($dashes as $dash) {
    $dash->remove();
}
unset($dashes,$dash);

/* remove link to home */
$home = $this->install->xpdo->getObject('modMenu',array('text' => 'home'));
if ($home != null) $home->remove();
unset($home);

/* remove logout link */
$logout = $this->install->xpdo->getObject('modMenu',array('text' => 'logout'));
if ($logout != null) $logout->remove();
unset($logout);

/* remove no-longer-valid lexicon topics */
$roletopic = $this->install->xpdo->getObject('modLexiconTopic',array(
    'name' => 'role',
));
if ($roletopic != null) { $roletopic->remove(); }
unset($roletopic);

/* add category field to property sets */
$class = 'modPropertySet';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE {$table} ADD `category` INT( 10 ) UNSIGNED NOT NULL AFTER `name`;";
$description = 'Added new column `category` to '.$table.'.';
$this->processResults($class, $description, $sql);

/* add index to category field for property sets */
$sql = "ALTER TABLE {$table} ADD INDEX `category` ( `category` )";
$description = 'Added index for field `category` to '.$table.'.';
$this->processResults($class, $description, $sql);
unset($class,$description,$sql,$table);


/* add description field to menus */
$class = 'modMenu';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE {$table} ADD COLUMN `description` VARCHAR(255) NOT NULL AFTER `text`";
$description = 'Added new column `description` to '.$table.'.';
$this->processResults($class, $description, $sql);
unset($class,$description,$sql,$table);

/* change modAction context_key to namespace */
$class = 'modAction';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE {$table} CHANGE `context_key` `namespace` VARCHAR( 100 ) NOT NULL DEFAULT 'core'";
$description = 'Changed column `context_key` to `namespace` on '.$table.'.';
$this->processResults($class,$description,$sql);
unset($class,$description,$sql,$table);


/* fix not null `properties` columns on all element (and property set) tables to allow null */
$elements = array (
    'modChunk',
    'modPlugin',
    'modSnippet',
    'modTemplate',
    'modTemplateVar',
    'modPropertySet',
);
foreach ($elements as $class) {
    $table = $this->install->xpdo->getTableName($class);
    $sql = "ALTER TABLE {$table} CHANGE `properties` `properties` TEXT NULL";
    $description = 'Fixing allow null for ' . $class . '.`properties`.';
    $this->processResults($class, $description, $sql);
}
unset($elements,$class,$description,$sql,$table);

/* remove connector context */
if ($connectorContext = $this->install->xpdo->getObject('modContext', 'connector')) {
    if ($connectorContext->remove()) {
        $this->results[] = array(
            'class' => 'success',
            'msg' => '<p class="ok">Successfully removed data from table for class modContext<br /><small>Removed connector context.</small></p>'
        );
    } else {
        $this->results[] = array(
            'class' => 'warning',
            'msg' => '<p class="notok">Error removing data from table for class modContext<br /><small>Could not remove connector context.</small></p>'
        );
    }
}
unset($connectorContext);

/* remove connector context related ACLs */
if ($connectorContextACLs = $this->install->xpdo->getCollection('modAccessContext', array('target' => 'connector'))) {
    foreach ($connectorContextACLs as $acl) {
        if ($acl->remove()) {
            $this->results[] = array(
                'class' => 'success',
                'msg' => '<p class="ok">Successfully removed data for class modAccessContext<br /><small>Removed connector context ACLs.</small></p>'
            );
        } else {
            $this->results[] = array(
                'class' => 'warning',
                'msg' => '<p class="notok">Error removing data for class modAccessContext<br /><small>Could not remove connector context ACLs.</small></p>'
            );
        }
    }
}
unset($connectorContextACLs, $acl);

/* remove connector context related ResourceGroup ACLs */
if ($connectorContextACLs = $this->install->xpdo->getCollection('modAccessResourceGroup', array('context_key' => 'connector'))) {
    foreach ($connectorContextACLs as $acl) {
        if ($acl->remove()) {
            $this->results[] = array(
                'class' => 'success',
                'msg' => '<p class="ok">Successfully removed data for class modAccessResourceGroup<br /><small>Removed connector context ACLs.</small></p>'
            );
        } else {
            $this->results[] = array(
                'class' => 'warning',
                'msg' => '<p class="notok">Error removing data for class modAccessResourceGroup<br /><small>Could not remove connector context ACLs.</small></p>'
            );
        }
    }
}
unset($connectorContextACLs, $acl);

/* fix change in xtypes for system settings */
$cbtypes = array('template','charset','language','rte');
foreach ($cbtypes as $xt) {
    $cbs = $this->install->xpdo->getCollection('modSystemSetting',array(
        'xtype' => 'combo-'.$xt,
    ));
    foreach ($cbs as $cb) {
        $cb->set('xtype','modx-combo-'.$xt);
        $cb->save();
    }
}
$this->results[] = array(
    'class' => 'success',
    'msg' => '<p class="ok">Successfully fixed xtypes for modSystemSettings.</small></p>'
);
unset($cbtypes,$xt,$cbs,$cb);

/* add property set foreign key to PluginEvents table */
$class = 'modPluginEvent';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE {$table} ADD `propertyset` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `priority`;";
$description = 'Added new column `propertyset` to '.$table.'.';
$this->processResults($class, $description, $sql);

/* modify modTransportPackage.manifest from mediumtext to text */
$class = 'transport.modTransportPackage';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE {$table} CHANGE `manifest` TEXT NULL;";
$description = 'Modified column `manifest` to TEXT from MEDIUMTEXT on '.$table.'.';
$this->processResults($class, $description, $sql);

/* add parent key to modCategory table */
$class = 'modCategory';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE {$table} ADD `parent` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `id`;";
$description = 'Added new column `parent` to '.$table.'.';
$this->processResults($class, $description, $sql);
