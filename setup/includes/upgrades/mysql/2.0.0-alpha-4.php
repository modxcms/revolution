<?php
/**
 * Handles all upgrades related to Revolution 2.0.0-alpha-2
 *
 * @package setup
 * @subpackage upgrades
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
$description = $this->install->lexicon('add_moduser_classkey');
$sql = "ALTER TABLE {$table} ADD COLUMN `class_key` VARCHAR(100) NOT NULL DEFAULT 'modUser' AFTER `cachepwd`";
$this->processResults($class,$description,$sql);


$class = 'modAction';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'lang_foci','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `lang_foci` TEXT AFTER `haslayout`";
$this->processResults($class,$description,$sql);


$class = 'modSystemSetting';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'namespace','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `namespace` VARCHAR(40) NOT NULL DEFAULT 'core'";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_column',array('column' => 'editedon','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `editedon` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_column',array('column' => 'area','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `area` VARCHAR(255) NOT NULL";
$this->processResults($class,$description,$sql);


$class = 'modContextSetting';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'namespace','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `namespace` VARCHAR(40) NOT NULL DEFAULT 'core'";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_column',array('column' => 'editedon','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `editedon` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_column',array('column' => 'area','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `area` VARCHAR(255) NOT NULL";
$this->processResults($class,$description,$sql);


$class = 'modUserSetting';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'namespace','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `namespace` VARCHAR(40) NOT NULL DEFAULT 'core'";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_column',array('column' => 'editedon','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `editedon` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('add_column',array('column' => 'area','table' => $table));
$sql = "ALTER TABLE {$table} ADD COLUMN `area` VARCHAR(255) NOT NULL";
$this->processResults($class,$description,$sql);


$class = 'modLexiconFocus';
$table = $this->config['table_prefix'].'lexicon_foci';
$description = $this->install->lexicon('lexiconfocus_drop_pk');
$sql = "ALTER TABLE {$table} DROP PRIMARY KEY";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon('lexiconfocus_add_id');
$sql = "ALTER TABLE {$table} ADD COLUMN `id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
$lexiconFocusChanged = $this->processResults($class,$description,$sql);
if (!$lexiconFocusChanged) {
    $description = $this->install->lexicon('lexiconfocus_add_pk');
    $sql = "ALTER TABLE {$table} ADD PRIMARY KEY (`id`)";
    $this->processResults($class,$description,$sql);
}
$description = $this->install->lexicon('lexiconfocus_alter_pk');
$sql = "ALTER TABLE {$table} ADD UNIQUE INDEX `foci` (`name`,`namespace`)";
$this->processResults($class,$description,$sql);


$class = 'modLexiconEntry';
$focusTable = $this->config['table_prefix'].'lexicon_foci';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon('lexiconentry_createdon_null');
$sql = "ALTER TABLE {$table} CHANGE COLUMN `createdon` `createdon` DATETIME NULL";
$this->processResults($class,$description,$sql);
if ($lexiconFocusChanged) {
    $description = $this->install->lexicon('lexiconentry_focus_alter_int');
$sql = "UPDATE {$table} `e`, {$focusTable} `f` SET `e`.`focus` = `f`.`id` WHERE `e`.`focus` = `f`.`name` AND `e`.`namespace` = `f`.`namespace`";
    $this->processResults($class,$description,$sql);
}
$description = $this->install->lexicon('lexiconentry_focus_alter');
$sql = "ALTER TABLE {$table} CHANGE COLUMN `focus` `focus` INT(10) unsigned NOT NULL DEFAULT 1";
$this->processResults($class,$description,$sql);
