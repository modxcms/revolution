<?php
/**
 * Specific upgrades for Revolution 2.2.1-pl
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

/** Add properties field to modResource */
$class = 'modResource';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'properties','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'properties'));

/* add session_stale field to modUser */
$class = 'modUser';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'session_stale','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'session_stale'));

/* modify nullability and add index to modSession.access */
$class = 'modSession';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('modify_column',array('column' => 'access','table' => $table, 'old' => 'NULL', 'new' => 'NOT NULL'));
$this->processResults($class, $description, array($modx->manager, 'alterField'), array($class, 'access'));

$description = $this->install->lexicon('add_index',array('index' => 'access','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addIndex'), array($class, 'access'));
