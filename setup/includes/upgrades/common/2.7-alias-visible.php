<?php
/**
 * Common upgrade script for 2.7 modResource
 *
 * @var modX $modx
 * @package setup
 */

/* add modResource.alias_visible field */

use MODX\Revolution\modResource;

$class = modResource::class;
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column', ['column' => 'alias_visible','table' => $table]);
$this->processResults($class, $description, [$modx->manager, 'addField'], [$class, 'alias_visible']);
