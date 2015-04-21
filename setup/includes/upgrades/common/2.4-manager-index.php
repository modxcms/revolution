<?php
/**
 * New Index for modAccessResourceGroup
 *
 * @var modX $modx
 * @package setup
 */

/* add modAccessResourceGroup.principal_class index */
$class = 'modManagerLog';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_index',array('index' => 'user_occured','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'user_occured'));
