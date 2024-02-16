<?php
/**
 * MySQL upgrade script for 2.8.7
 *
 * @var modX $modx
 * @package setup
 */

$class = 'modManagerLog';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('alter_column', array('column' => 'occurred', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'occurred'));
