<?php
/**
 * Specific upgrades for Revolution 2.0.0-beta-2
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


/* add index to name,language,topic,namespace fields for lexicon entries */
$class = 'modLexiconEntry';
$table = $this->install->xpdo->getTableName($class);

$sql = "ALTER TABLE {$table} ADD INDEX `name` ( `name` )";
$description = $this->install->lexicon('add_index',array('index' => 'name','table' => $table));
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `namespace` ( `namespace` )";
$description = $this->install->lexicon('add_index',array('index' => 'namespace','table' => $table));
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `topic` ( `topic` )";
$description = $this->install->lexicon('add_index',array('index' => 'topic','table' => $table));
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `language` ( `language` )";
$description = $this->install->lexicon('add_index',array('index' => 'language','table' => $table));
$this->processResults($class, $description, $sql);
unset($class,$description,$sql,$table);
