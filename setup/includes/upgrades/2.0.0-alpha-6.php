<?php
/**
 * Handles all upgrades related to Revolution 2.0.0-alpha-6
 *
 * @package setup
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
$description = 'Added new index on `published`.';
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `pub_date` (`pub_date`)";
$description = 'Added new index on `pub_date`.';
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `unpub_date` (`unpub_date`)";
$description = 'Added new index on `unpub_date`.';
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `menuindex` (`menuindex`)";
$description = 'Added new index on `menuindex`.';
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `isfolder` (`isfolder`)";
$description = 'Added new index on `isfolder`.';
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `template` (`template`)";
$description = 'Added new index on `published`.';
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `searchable` (`searchable`)";
$description = 'Added new index on `searchable`.';
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `cacheable` (`cacheable`)";
$description = 'Added new index on `cacheable`.';
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `hidemenu` (`hidemenu`)";
$description = 'Added new index on `hidemenu`.';
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} ADD INDEX `context_key` (`context_key`)";
$description = 'Added new index on `context_key`.';
$this->processResults($class, $description, $sql);

$sql = "ALTER TABLE {$table} CHANGE `template` `template` INT( 10 ) NOT NULL DEFAULT '0'";
$description = 'Changed default value for template column to 0.';
$this->processResults($class, $description, $sql);

$class = 'modPlugin';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added index on `locked` missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);
$description = 'Added index on `disabled` missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD INDEX `disabled` (`disabled`)";
$this->processResults($class,$description,$sql);
$description = 'Added index on `moduleguid` missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD INDEX `moduleguid` (`moduleguid`)";
$this->processResults($class,$description,$sql);
$description = 'Added index on `category` missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);

$class = 'modSnippet';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added index on `locked` missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);
$description = 'Added index on `category` missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);
$description = 'Added index on `moduleguid` missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD INDEX `moduleguid` (`moduleguid`)";
$this->processResults($class,$description,$sql);

$class = 'modTemplate';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added properties field missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD COLUMN `properties` TEXT AFTER `locked`";
$this->processResults($class,$description,$sql);
$description = 'Added index on `category` missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);
$description = 'Added index on `locked` missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);

$class = 'modTemplateVar';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added properties field missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD COLUMN `properties` TEXT AFTER `default_text`";
$this->processResults($class,$description,$sql);
$description = 'Added index on `category` missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);
$description = 'Added index on `locked` missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);

$class = 'modChunk';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added properties field missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD COLUMN `properties` TEXT AFTER `locked`";
$this->processResults($class,$description,$sql);
$description = 'Add FK index on `category` missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD INDEX `category` (`category`)";
$this->processResults($class,$description,$sql);
$description = 'Add index on `locked` missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD INDEX `locked` (`locked`)";
$this->processResults($class,$description,$sql);