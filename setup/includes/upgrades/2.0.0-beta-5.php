<?php
/**
 * Specific upgrades for Revolution 2.0.0-beta-5
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

/* add unique index to modLexiconEntry */
$class = 'modLexiconEntry';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['add_index'],'uniqentry',$table);
$sql = "ALTER TABLE  {$table} ADD UNIQUE `uniqentry` (`name`,`topic`,`namespace`,`language`)";
$this->processResults($class, $description, $sql);
unset($class,$table,$sql,$description);

