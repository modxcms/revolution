<?php
/**
 * Handles all upgrades related to Revolution 2.0.0-alpha-6
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


$class = 'modResource';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE {$table} ADD INDEX `published` (`published`)";
$description = sprintf($this->install->lexicon['add_index'],'published',$table);
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `pub_date` (`pub_date`)";
$description = sprintf($this->install->lexicon['add_index'],'pub_date',$table);
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `unpub_date` (`unpub_date`)";
$description = sprintf($this->install->lexicon['add_index'],'unpub_date',$table);
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `menuindex` (`menuindex`)";
$description = sprintf($this->install->lexicon['add_index'],'menuindex',$table);
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `isfolder` (`isfolder`)";
$description = sprintf($this->install->lexicon['add_index'],'isfolder',$table);
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `template` (`template`)";
$description = sprintf($this->install->lexicon['add_index'],'template',$table);
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `searchable` (`searchable`)";
$description = sprintf($this->install->lexicon['add_index'],'searchable',$table);
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `cacheable` (`cacheable`)";
$description = sprintf($this->install->lexicon['add_index'],'cacheable',$table);
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `hidemenu` (`hidemenu`)";
$description = sprintf($this->install->lexicon['add_index'],'hidemenu',$table);
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `context_key` (`context_key`)";
$description = sprintf($this->install->lexicon['add_index'],'context_key',$table);
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} CHANGE `template` `template` INT( 10 ) NOT NULL DEFAULT '0'";
$description = sprintf($this->install->lexicon['change_default_value'],'template','0',$table);
$this->processResults($class, $description, $sql);

$class = 'modPlugin';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['add_index'],'locked',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['add_index'],'disabled',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `disabled` (`disabled`)";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['add_index'],'moduleguid',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `moduleguid` (`moduleguid`)";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['add_index'],'category',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);

$class = 'modSnippet';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['add_index'],'locked',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['add_index'],'category',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['add_index'],'moduleguid',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `moduleguid` (`moduleguid`)";
$this->processResults($class,$description,$sql);

$class = 'modTemplate';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['add_column'],'properties',$table);
$sql = "ALTER TABLE {$table} ADD COLUMN `properties` TEXT AFTER `locked`";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['add_index'],'category',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['add_index'],'locked',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);

$class = 'modTemplateVar';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['add_column'],'properties',$table);
$sql = "ALTER TABLE {$table} ADD COLUMN `properties` TEXT AFTER `default_text`";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['add_index'],'category',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['add_index'],'locked',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);

$class = 'modChunk';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['add_column'],'properties',$table);
$sql = "ALTER TABLE {$table} ADD COLUMN `properties` TEXT AFTER `locked`";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['add_index'],'category',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['add_index'],'locked',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);