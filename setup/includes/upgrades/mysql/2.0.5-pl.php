<?php
/**
 * Specific upgrades for Revolution 2.0.5
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

/* add rank field to FC rules */
$class = 'modActionDom';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'rank','table' => $table));
$sql = "ALTER TABLE {$table} ADD `rank` INT(11) NULL DEFAULT '0' AFTER `for_parent`";
$this->processResults($class,$description,$sql);

