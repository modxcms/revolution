<?php
/**
 * Handles all upgrades related to Revolution 2.0.0-alpha-2
 *
 * @package setup
 */
/* handle new class creation */
$classes = array(
    'modNamespace',
    'modLexiconEntry',
    'modLexiconLanguage',
    'modLexiconTopic',
);
if (!empty($classes)) {
    $this->createTable($classes);
}


/* add table structure changes here for upgrades to previous Revolution installations */
$class = 'modUser';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added class_key field to support modUser derivatives';
$sql = "ALTER TABLE {$table} ADD COLUMN `class_key` VARCHAR(100) NOT NULL DEFAULT 'modUser' AFTER `cachepwd`";
$this->processResults($class,$description,$sql);


$class = 'modAction';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added modAction `lang_foci` field.';
$sql = "ALTER TABLE {$table} ADD COLUMN `lang_foci` TEXT AFTER `haslayout`";
$this->processResults($class,$description,$sql);


$class = 'modSystemSetting';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added modSystemSetting `namespace`.';
$sql = "ALTER TABLE {$table} ADD COLUMN `namespace` VARCHAR(40) NOT NULL DEFAULT 'core'";
$this->processResults($class,$description,$sql);
$description = 'Added modSystemSetting `editedon`.';
$sql = "ALTER TABLE {$table} ADD COLUMN `editedon` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
$this->processResults($class,$description,$sql);
$description = 'Added modSystemSetting `area`.';
$sql = "ALTER TABLE {$table} ADD COLUMN `area` VARCHAR(255) NOT NULL";
$this->processResults($class,$description,$sql);


$class = 'modContextSetting';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added modContextSetting `namespace`.';
$sql = "ALTER TABLE {$table} ADD COLUMN `namespace` VARCHAR(40) NOT NULL DEFAULT 'core'";
$this->processResults($class,$description,$sql);
$description = 'Added modContextSetting `editedon`.';
$sql = "ALTER TABLE {$table} ADD COLUMN `editedon` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
$this->processResults($class,$description,$sql);
$description = 'Added modContextSetting `area`.';
$sql = "ALTER TABLE {$table} ADD COLUMN `area` VARCHAR(255) NOT NULL";
$this->processResults($class,$description,$sql);


$class = 'modUserSetting';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added modUserSetting `namespace`.';
$sql = "ALTER TABLE {$table} ADD COLUMN `namespace` VARCHAR(40) NOT NULL DEFAULT 'core'";
$this->processResults($class,$description,$sql);
$description = 'Added modUserSetting `editedon`.';
$sql = "ALTER TABLE {$table} ADD COLUMN `editedon` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
$this->processResults($class,$description,$sql);
$description = 'Added modUserSetting `area`.';
$sql = "ALTER TABLE {$table} ADD COLUMN `area` VARCHAR(255) NOT NULL";
$this->processResults($class,$description,$sql);


$class = 'modLexiconFocus';
$table = $this->config['table_prefix'].'lexicon_foci';
$description = 'Dropped modLexiconFocus PRIMARY KEY';
$sql = "ALTER TABLE {$table} DROP PRIMARY KEY";
$this->processResults($class,$description,$sql);
$description = 'Added modLexiconFocus `id` column.';
$sql = "ALTER TABLE {$table} ADD COLUMN `id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
$lexiconFocusChanged = $this->processResults($class,$description,$sql);
if (!$lexiconFocusChanged) {
    $description = 'Added modLexiconFocus PRIMARY KEY to `id` column';
    $sql = "ALTER TABLE {$table} ADD PRIMARY KEY (`id`)";
    $this->processResults($class,$description,$sql);
}
$description = 'Changed modLexiconFocus `name` from PRIMARY KEY to UNIQUE KEY';
$sql = "ALTER TABLE {$table} ADD UNIQUE INDEX `foci` (`name`,`namespace`)";
$this->processResults($class,$description,$sql);


$class = 'modLexiconEntry';
$focusTable = $this->config['table_prefix'].'lexicon_foci';
$table = $this->install->xpdo->getTableName($class);
$description = 'Changed modLexiconEntry `createdon` to allow NULL.';
$sql = "ALTER TABLE {$table} CHANGE COLUMN `createdon` `createdon` DATETIME NULL";
$this->processResults($class,$description,$sql);
if ($lexiconFocusChanged) {
    $description = 'Updated modLexiconEntry `focus` column data from string to new int foreign key from modLexiconTopic.';
$sql = "UPDATE {$table} `e`, {$focusTable} `f` SET `e`.`focus` = `f`.`id` WHERE `e`.`focus` = `f`.`name` AND `e`.`namespace` = `f`.`namespace`";
    $this->processResults($class,$description,$sql);
}
$description = 'Changed modLexiconEntry `focus` from VARCHAR(100) to INT(10).';
$sql = "ALTER TABLE {$table} CHANGE COLUMN `focus` `focus` INT(10) unsigned NOT NULL DEFAULT 1";
$this->processResults($class,$description,$sql);
