<?php
/**
 * Specific upgrades for Revolution 2.3.0-rc1
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(
    'modExtensionPackage',
    'modUserGroupSetting'
);
if (!empty($classes)) {
    $this->createTable($classes);
}
unset($classes);

/* change modActionDom.action to a VARCHAR */
$class = 'modActionDom';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('modify_column',array('column' => 'action','table' => $table, 'old' => 'INT', 'new' => 'NVARCHAR(255)'));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'action'));

/* change modFormCustomizationSet.action to a VARCHAR */
$class = 'modFormCustomizationSet';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('modify_column',array('column' => 'action','table' => $table, 'old' => 'INT', 'new' => 'NVARCHAR(255)'));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'action'));

/* change modActionField.action to a VARCHAR */
$class = 'modActionField';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('modify_column',array('column' => 'action','table' => $table, 'old' => 'INT', 'new' => 'NVARCHAR(255)'));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'action'));

/* update data map for FC fields upgrades */
include dirname(dirname(__FILE__)).'/common/2.3-fc-action.php';

/* change modMenu.action to a VARCHAR */
$class = 'modMenu';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('modify_column',array('column' => 'action','table' => $table, 'old' => 'INT', 'new' => 'NVARCHAR(255)'));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'action'));
/* add modMenu.namespace field */
$description = $this->install->lexicon('add_column',array('column' => 'namespace','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'namespace'));

/* migrate extension_packages setting to DB table */
include dirname(dirname(__FILE__)).'/common/2.3-extension-packages.php';

/* add modContext.name field */
$class = 'modContext';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'name','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'name'));

/* add index on modContext.name */
$description = $this->install->lexicon('add_index',array('index' => 'name','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'name'));

/* remove old menus */
$modx->removeCollection('modMenu', array('text:IN' => array('new_document', 'new_static_resource', 'new_symlink', 'new_weblink')));

/* Remove context_key from forward_merge_excludes System Setting */
$object = $modx->getObject('modSystemSetting', array('key' => 'forward_merge_excludes'));
if ($object) {
    $value = $object->get('value');
    $exploded = explode(',', $value);
    $exploded = array_diff($exploded, array('context_key'));
    $object->set('value', implode(',', $exploded));
    $object->save();
}

/* Update modTransportPackage version_* and release_index fields (from 2.2.10-pl) */
$class = 'transport.modTransportPackage';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('modify_column',array('column' => 'version_major', 'old' => 'tinyint', 'new' => 'smallint', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'version_major'));
$description = $this->install->lexicon('modify_column',array('column' => 'version_minor', 'old' => 'tinyint', 'new' => 'smallint', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'version_minor'));
$description = $this->install->lexicon('modify_column',array('column' => 'version_patch', 'old' => 'tinyint', 'new' => 'smallint', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'version_patch'));
$description = $this->install->lexicon('modify_column',array('column' => 'release_index', 'old' => 'tinyint', 'new' => 'smallint', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'release_index'));

/* modify index on modCategory */
$class = 'modCategory';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('remove_index',array('index' => 'category', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'removeIndex'), array($class, 'category'));
$description = $this->install->lexicon('add_index',array('index' => 'category', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'category'));
