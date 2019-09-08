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

$description = $this->install->lexicon('alter_column',array('column' => 'description','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'description'));
