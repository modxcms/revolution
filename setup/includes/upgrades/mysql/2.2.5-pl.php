<?php
/**
 * Specific upgrades for Revolution 2.2.5-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(
);
if (!empty($classes)) {
    $this->createTable($classes);
}
unset($classes);

/* Add cache_refresh_idx to modResource table (MySQL-specific) */
$class = 'modResource';
$table = $modx->getTableName('modResource');
$description = $this->install->lexicon('add_index',array('index' => 'cache_refresh_idx', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'cache_refresh_idx'));
