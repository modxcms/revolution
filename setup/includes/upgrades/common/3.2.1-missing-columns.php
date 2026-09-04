<?php

/**
 * Idempotent upgrade script: add content_type.icon and dashboard.customizable
 * if missing (fixes broken upgrades where 3.0.0 migrations did not complete).
 *
 * @var modInstallVersion $this
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modContentType;
use MODX\Revolution\modDashboard;

$class = modContentType::class;
$column = 'icon';
$table = $modx->getTableName($class);
$stmt = $modx->query('SHOW COLUMNS FROM ' . $modx->escape($table) . ' LIKE ' . $modx->quote($column));
$iconExists = $stmt && $stmt->fetch() !== false;

if (!$iconExists) {
    $description = $this->install->lexicon('add_column', ['column' => $column, 'table' => $table]);
    $this->processResults($class, $description, [$modx->manager, 'addField'], [$class, $column]);
}

$class = modDashboard::class;
$column = 'customizable';
$table = $modx->getTableName($class);
$stmt = $modx->query('SHOW COLUMNS FROM ' . $modx->escape($table) . ' LIKE ' . $modx->quote($column));
$customizableExists = $stmt && $stmt->fetch() !== false;

if (!$customizableExists) {
    $description = $this->install->lexicon('add_column', ['column' => $column, 'table' => $table]);
    $this->processResults($class, $description, [$modx->manager, 'addField'], [$class, $column]);
}
