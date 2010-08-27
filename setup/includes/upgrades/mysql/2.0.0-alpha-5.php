<?php
/**
 * Handles all upgrades related to Revolution 2.0.0-alpha-5
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

/* add table structure changes here for upgrades to previous Revolution installations */
$class = 'modAction';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('rename_column',array('old' => 'lang_foci','new' => 'lang_topics','table' => $table));
$sql = "ALTER TABLE {$table} CHANGE COLUMN `lang_foci` `lang_topics` TEXT";
$this->processResults($class,$description,$sql);

$class = 'modLexiconEntry';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('rename_column',array('old' => 'focus','new' => 'topic','table' => $table));
$sql = "ALTER TABLE {$table} CHANGE `focus` `topic` INT( 10 ) UNSIGNED NOT NULL DEFAULT '1'";
$this->processResults($class,$description,$sql);

$focusTable = $this->config['table_prefix'].'lexicon_foci';
$topicTable = $this->install->xpdo->getTableName('modLexiconTopic');
$description = $this->install->lexicon('rename_table',array('old' => $focusTable,'new' => $topicTable));
$sql = "RENAME TABLE {$focusTable} TO {$topicTable}";
$this->processResults($class,$description,$sql);