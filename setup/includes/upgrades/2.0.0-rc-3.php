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
$description = sprintf($this->install->lexicon['change_column'],'topic topic','topic topic',$table);
$sql = "ALTER TABLE {$table} CHANGE `topic` `topic` VARCHAR(228) NOT NULL DEFAULT 'default'";
$this->processResults($class,$description,$sql);
