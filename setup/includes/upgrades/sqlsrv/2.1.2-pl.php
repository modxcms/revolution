<?php
/**
 * Specific upgrades for Revolution 2.1.2-pl
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

/* [#5072] add missing primary key on modEvent */
$class = 'modEvent';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('drop_index',array('index' => 'name','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'removeIndex'), array($class, 'name'));

$description = $this->install->lexicon('change_default_value',array('column' => 'name', 'value'=>'NULL', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'name'));

$description = $this->install->lexicon('add_index',array('index' => 'PRIMARY','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'PRIMARY'));

/* [#2870] Change internalKey default value to NULL */
$class = 'modUserProfile';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('change_default_value',array('column' => 'internalKey', 'value'=>'NULL', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'internalKey'));
