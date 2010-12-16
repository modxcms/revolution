<?php
/**
 * Specific upgrades for Revolution 2.0.6
 *
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

/* adjust session data field to hold more than 64kb (#3103) */
$class = 'modSession';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('change_column',array(
    'old' => 'data TEXT',
    'new' => 'data MEDIUMTEXT',
    'table' => $table,
));
$sql = "ALTER TABLE {$table} CHANGE `data` `data` MEDIUMTEXT NOT NULL";
$this->processResults($class,$description,$sql);
