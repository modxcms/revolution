<?php
/**
 * Common upgrade script for 2.7 modResource
 *
 * @var modX $modx
 * @package setup
 */

/* modify modResource.description field */

use MODX\Revolution\modResource;

$class = modResource::class;
$table = $modx->getTableName($class);

$description = $this->install->lexicon('alter_column', ['column' => 'description','table' => $table]);
$this->processResults($class, $description, [$modx->manager, 'alterField'], [$class, 'description']);
