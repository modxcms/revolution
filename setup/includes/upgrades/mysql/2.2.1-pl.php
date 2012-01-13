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