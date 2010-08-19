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
$description = $this->install->lexicon('add_index',array('index' => 'published','table' => $table));
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `pub_date` (`pub_date`)";
$description = $this->install->lexicon('add_index',array('index' => 'pub_date','table' => $table));
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `unpub_date` (`unpub_date`)";
$description = $this->install->lexicon('add_index',array('index' => 'unpub_date','table' => $table));
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `menuindex` (`menuindex`)";
$description = $this->install->lexicon('add_index',array('index' => 'menuindex','table' => $table));
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `isfolder` (`isfolder`)";
$description = $this->install->lexicon('add_index',array('index' => 'isfolder','table' => $table));
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `template` (`template`)";
$description = $this->install->lexicon('add_index',array('index' => 'template','table' => $table));
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `searchable` (`searchable`)";
$description = $this->install->lexicon('add_index',array('index' => 'searchable','table' => $table));
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `cacheable` (`cacheable`)";
$description = $this->install->lexicon('add_index',array('index' => 'cacheable','table' => $table));
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `hidemenu` (`hidemenu`)";
$description = $this->install->lexicon('add_index',array('index' => 'hidemenu','table' => $table));
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `context_key` (`context_key`)";
$description = $this->install->lexicon('add_index',array('index' => 'context_key','table' => $table));
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} CHANGE `template` `template` INT( 10 ) NOT NULL DEFAULT '0'";
$description = $this->install->lexicon('change_default_value',array(
    'column' => 'template',
    'value' => '0',
    'table' => $table,
));
$this->processResults($class, $description, $sql);

$class = 'modPlugin';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_index',array('index' => 'locked','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_index',array('index' => 'disabled','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `disabled` (`disabled`)";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_index',array('index' => 'moduleguid','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `moduleguid` (`moduleguid`)";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_index',array('index' => 'category','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);

$class = 'modSnippet';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_index',array('index' => 'locked','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_index',array('index' => 'category','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_index',array('index' => 'moduleguid','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `moduleguid` (`moduleguid`)";
$this->processResults($class,$description,$sql);

$class = 'modTemplate';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'properties','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `properties` TEXT AFTER `locked`";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_index',array('index' => 'category','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_index',array('index' => 'locked','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);

$class = 'modTemplateVar';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'properties','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `properties` TEXT AFTER `default_text`";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_index',array('index' => 'category','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_index',array('index' => 'locked','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);

$class = 'modChunk';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'properties','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `properties` TEXT AFTER `locked`";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_index',array('index' => 'category','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_index',array('index' => 'locked','table' => $table));
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);