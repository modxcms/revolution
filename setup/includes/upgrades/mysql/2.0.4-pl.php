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