<?php
/**
 * Common upgrade script to upgrade dashboard tables.
 *
 * @var modX
 *
 * @package setup
 */

/* add modResource.alias_visible field */

$class = 'modDashboard';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column', ['column' => 'customizable', 'table' => $table]);
$this->processResults($class, $description, [$modx->manager, 'addField'], [$class, 'customizable']);

$class = 'modDashboardWidget';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column', ['column' => 'permission', 'table' => $table]);
$this->processResults($class, $description, [$modx->manager, 'addField'], [$class, 'permission']);

$description = $this->install->lexicon('add_column', ['column' => 'properties', 'table' => $table]);
$this->processResults($class, $description, [$modx->manager, 'addField'], [$class, 'properties']);

$class = 'modDashboardWidgetPlacement';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column', ['column' => 'user', 'table' => $table]);
$this->processResults($class, $description, [$modx->manager, 'addField'], [$class, 'user']);

$description = $this->install->lexicon('add_column', ['column' => 'size', 'table' => $table]);
$this->processResults($class, $description, [$modx->manager, 'addField'], [$class, 'size']);

$description = $this->install->lexicon('drop_index', ['index' => 'PRIMARY', 'table' => $table]);
$this->processResults($class, $description, [$modx->manager, 'removeIndex'], [$class, 'PRIMARY']);
$description = $this->install->lexicon('add_index', ['index' => 'PRIMARY', 'table' => $table]);
$this->processResults($class, $description, [$modx->manager, 'addIndex'], [$class, 'PRIMARY']);