<?php
/**
 * Common upgrade script for 3.0 for adding new columns to elements tables.
 *
 * @var modX $modx
 * @package setup
 */

$columns = ['createdby', 'createdon', 'editedby', 'editedon'];
$classes = $modx->getDescendants('modElement');
foreach ($classes as $class) {
    $table = $modx->getTableName($class);
    foreach ($columns as $column) {
        $description = $this->install->lexicon('add_column', ['column' => $column, 'table' => $table]);
        $this->processResults($class, $description, [$modx->manager, 'addField'], [$class, $column]);
    }
    $description = $this->install->lexicon('add_index', ['index' => 'editedon', 'table' => $table]);
    $this->processResults($class, $description, [$modx->manager, 'addIndex'], [$class, 'editedon']);
}