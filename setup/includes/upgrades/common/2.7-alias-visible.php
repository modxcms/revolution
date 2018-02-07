<?php
/**
 * Common upgrade script for 2.6 modResource
 *
 * @var modX $modx
 * @package setup
 */

/* add modResource.alias_visible field */
$class = 'modResource';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column',array('column' => 'alias_visible','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'alias_visible'));
