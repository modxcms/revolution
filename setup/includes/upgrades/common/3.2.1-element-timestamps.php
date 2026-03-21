<?php

/**
 * Add createdon and editedon fields to element and related tables.
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modCategory;
use MODX\Revolution\modChunk;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modPluginEvent;
use MODX\Revolution\modSnippet;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modTemplateVar;
use MODX\Revolution\modTemplateVarResource;
use MODX\Revolution\modTemplateVarResourceGroup;
use MODX\Revolution\modTemplateVarTemplate;

$classes = [
    modCategory::class,
    modChunk::class,
    modPlugin::class,
    modPluginEvent::class,
    modSnippet::class,
    modTemplate::class,
    modTemplateVar::class,
    modTemplateVarResource::class,
    modTemplateVarResourceGroup::class,
    modTemplateVarTemplate::class,
];

foreach ($classes as $class) {
    $table = $modx->getTableName($class);

    $description = $this->install->lexicon('add_column', ['column' => 'createdon', 'table' => $table]);
    $this->processResults($class, $description, [$modx->manager, 'addField'], [$class, 'createdon']);

    $description = $this->install->lexicon('add_column', ['column' => 'editedon', 'table' => $table]);
    $this->processResults($class, $description, [$modx->manager, 'addField'], [$class, 'editedon']);
}
