<?php
/**
 * Specific upgrades for Revolution 2.0.0-rc-3
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

/* adjust entry table [#MODX-2032] */
$class = 'modLexiconEntry';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('change_column',array(
    'old' => 'topic VARCHAR(255)',
    'new' => 'topic VARCHAR(228)',
    'table' => $table,
));
$sql = "ALTER TABLE {$table} CHANGE `topic` `topic` VARCHAR(228) NOT NULL DEFAULT 'default'";
$this->processResults($class,$description,$sql);
