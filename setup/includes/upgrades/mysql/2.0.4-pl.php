<?php
/**
 * Specific upgrades for Revolution 2.0.4
 *
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(
    'modClassMap',
);
if (!empty($classes)) {
    $this->createTable($classes);
}
unset($classes);

/* ensure documents are modDocument, not modResource */
$class = 'modResource';
$table = $this->install->xpdo->getTableName($class);
$sql = "UPDATE {$table} SET `class_key` = 'modDocument' WHERE `class_key` = 'modResource'";
$this->install->xpdo->exec($sql);

/* add for_parent field to FC rules */
$class = 'modActionDom';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'for_parent','table' => $table));
$sql = "ALTER TABLE {$table} ADD `for_parent` TINYINT(1) NULL DEFAULT '0' AFTER `active`";
$this->processResults($class,$description,$sql);
