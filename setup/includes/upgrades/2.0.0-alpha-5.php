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
$description = sprintf($this->install->lexicon['rename_table'],'lang_foci','lang_topics',$table);
$sql = "ALTER TABLE {$table} CHANGE COLUMN `lang_foci` `lang_topics` TEXT";
$this->processResults($class,$description,$sql);

$class = 'modLexiconEntry';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['rename_column'],'focus','topic',$table);
$sql = "ALTER TABLE {$table} CHANGE `focus` `topic` INT( 10 ) UNSIGNED NOT NULL DEFAULT '1'";
$this->processResults($class,$description,$sql);

$description = sprintf($this->install->lexicon['rename_table'],'modx_lexicon_foci','modx_lexicon_topics');
$focusTable = $this->config['table_prefix'].'lexicon_foci';
$topicTable = $this->install->xpdo->getTableName('modLexiconTopic');
$sql = "RENAME TABLE {$focusTable} TO {$topicTable}";
$this->processResults($class,$description,$sql);