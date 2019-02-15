<?php
/**
 * Upgrade script for adding icon field to content types.
 *
 * @var modX
 * @package setup
 */

$class = 'modContentType';
$column = 'icon';

$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column', ['column' => $column, 'table' => $table]);
$this->processResults($class, $description, [$modx->manager, 'addField'], [$class, $column]);
