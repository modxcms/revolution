<?php
/**
 * New Index for modAccessResourceGroup
 *
 * @var modX $modx
 * @package setup
 */

/* add modAccessResourceGroup.principal_class index */
$class = 'modAccessResourceGroup';
$table = $modx->getTableName($class);

$sql = $driver->dropIndex($table,'principal_class');
$description = $this->install->lexicon('drop_index',array('index' => 'principal_class','table' => $table));
$this->processResults($class,$description,$sql);

$description = $this->install->lexicon('add_index',array('index' => 'principal_class','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'principal_class'));
