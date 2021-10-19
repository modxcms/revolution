<?php

/**
 * Scripts removes obsolete field contentType from the main resource table
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modResource;

$class = modResource::class;
$table = $modx->getTableName($class);

$description = $this->install->lexicon('drop_column', ['column' => 'contentType', 'table' => $table]);
$this->processResults($class, $description, [$modx->manager, 'removeField'], [$class, 'contentType']);
