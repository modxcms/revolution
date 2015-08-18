<?php
/**
 * Common upgrade script for 2.4 modCategory
 *
 * @var modX $modx
 * @package setup
 */

/* add modCategory.rank field */
$class = 'modCategory';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column',array('column' => 'rank','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'rank'));

$description = $this->install->lexicon('add_index',array('index' => 'rank','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'rank'));