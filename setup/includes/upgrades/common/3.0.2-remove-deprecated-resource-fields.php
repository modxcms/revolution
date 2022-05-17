<?php

/**
 * Scripts removes deprecated fields from the main resource table
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modResource;

$class = modResource::class;
$table = $modx->getTableName($class);

$deprecatedFields = [
    'privateweb',
    'privatemgr',
    'donthit',
];

foreach ($deprecatedFields as $deprecatedField) {
    $description = $this->install->lexicon('drop_column', ['column' => $deprecatedField, 'table' => $table]);
    $this->processResults($class, $description, [$modx->manager, 'removeField'], [$class, $deprecatedField]);
}
