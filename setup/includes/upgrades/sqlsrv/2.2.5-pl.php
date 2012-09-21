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

/* Add name field to modContext */
$class = 'modContext';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'name','table' => $table));
$this->processResults($class, $description, array($modx->manager, 'addField'), array($class, 'name'));