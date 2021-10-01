<?php

/**
 * Common upgrade script for 3.0.0 modTemplate
 *
 * @var modX $modx
 * @package setup
 */

/* add modTemplate.preview_file field */
$class = 'modTemplate';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column', array('column' => 'preview_file', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'preview_file'));
