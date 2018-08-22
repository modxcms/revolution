<?php
/**
 * Scripts removes obsolete field contentType from the main resource table
 *
 * @var modX $modx
 * @package setup
 */

$class = 'modResource';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('drop_column', array('column' => 'contentType', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'removeField'), array($class, 'contentType'));
