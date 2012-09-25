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