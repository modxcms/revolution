<?php
/**
 * Handles all upgrades related to Revolution 2.0.0-alpha-1
 *
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(
    'modAccessAction',
    'modAccessElement',
    'modAccessMenu',
    'modAccessPolicy',
    'modAccessResource',
    'modAccessResourceGroup',
    'modAccessTemplateVar',
    'modAction',
    'modContextResource',
    'modContentType',
    'modManagerLog',
    'modMenu',
    'modWorkspace',
    'transport.modTransportPackage',
    'transport.modTransportProvider',
);
if (!empty($classes)) {
    $this->createTable($classes);
}

/* add table structure changes here for upgrades to previous Revolution installations */
$class = 'modResource';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE {$table} DROP INDEX `content_ft_idx`";
$description = sprintf($this->install->lexicon['remove_fulltext_index'],'content_ft_idx');
$removedOldFullTextIndex = $this->processResults($class,$description,$sql);

$sql = "ALTER TABLE {$table} ADD COLUMN `content_type` INT(11) unsigned NOT NULL DEFAULT 0 ";
$description = sprintf($this->install->lexicon['add_column'],'content_type',$table);
$this->processResults($class,$description,$sql);

if ($removedOldFullTextIndex) {
    $sql = "ALTER TABLE {$table} ADD FULLTEXT INDEX `content_ft_idx` (`pagetitle`, `longtitle`, `description`, `introtext`, `content`)";
    $description = $this->install->lexicon['added_content_ft_idx'];
    $this->processResults($class,$description,$sql);
}


$class = 'modUserGroup';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['add_column'],'parent',$table);
$sql = "ALTER TABLE {$table} ADD COLUMN `parent` INT(11) unsigned NOT NULL DEFAULT 0";
$this->processResults($class,$description,$sql);

$description = sprintf($this->install->lexicon['add_index'],'parent',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `parent` (`parent`)";
$this->processResults($class,$description,$sql);

$class = 'modUserGroupMember';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['add_column'],'role',$table);
$sql = "ALTER TABLE {$table} ADD COLUMN `role` INT(10) unsigned NOT NULL DEFAULT 0";
$this->processResults($class,$description,$sql);

$description = sprintf($this->install->lexicon['add_index'],'role',$table);
$sql = "ALTER TABLE {$table} ADD INDEX `role` (`role`)";
$this->processResults($class,$description,$sql);

$class = 'modUser';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon['added_cachepwd'];
$sql = "ALTER TABLE {$table} ADD COLUMN `cachepwd` VARCHAR(100) NOT NULL DEFAULT '' AFTER `password`";
$this->processResults($class,$description,$sql);


$class = 'modActiveUser';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon['alter_activeuser_action'];
$sql = "ALTER TABLE {$table} MODIFY COLUMN `action` VARCHAR(255)";
$this->processResults($class,$description,$sql);

$class = 'modAction';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['add_column'],'lang_foci',$table);
$sql = "ALTER TABLE {$table} ADD COLUMN `lang_foci` TEXT AFTER `haslayout`";
$this->processResults($class,$description,$sql);


$class = 'modSystemSetting';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['change_column'],'setting_name','key',$table);
$sql = "ALTER TABLE {$table} CHANGE COLUMN `setting_name` `key` VARCHAR(50) NOT NULL";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['change_column'],'setting_value','value',$table);
$sql = "ALTER TABLE {$table} CHANGE COLUMN `setting_value` `value` TEXT NOT NULL";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['add_column'],'xtype',$table);
$sql = "ALTER TABLE {$table} ADD COLUMN `xtype` VARCHAR(75) NOT NULL DEFAULT 'textfield'";
$this->processResults($class,$description,$sql);


$class = 'modContextSetting';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['add_column'],'xtype',$table);
$sql = "ALTER TABLE {$table} ADD COLUMN `xtype` VARCHAR(75) NOT NULL DEFAULT 'textfield'";
$this->processResults($class,$description,$sql);


$class = 'modUserSetting';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['change_column'],'setting_name','key',$table);
$sql = "ALTER TABLE {$table} CHANGE COLUMN `setting_name` `key` VARCHAR(50) NOT NULL";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['change_column'],'setting_value','value',$table);
$sql = "ALTER TABLE {$table} CHANGE COLUMN `setting_value` `value` TEXT NOT NULL";
$this->processResults($class,$description,$sql);
$description = sprintf($this->install->lexicon['add_column'],'xtype',$table);
$sql = "ALTER TABLE {$table} ADD COLUMN `xtype` VARCHAR(75) NOT NULL DEFAULT 'textfield'";
$this->processResults($class,$description,$sql);


$class = 'modManagerLog';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['change_column'],'class_key','classKey',$table);
$sql = "ALTER TABLE {$table} CHANGE COLUMN `class_key` `classKey` VARCHAR(100) NOT NULL";
$this->processResults($class,$description,$sql);

$class = 'modUserMessage';
$table = $this->install->xpdo->getTableName($class);
$description = $this->install->lexicon['alter_usermessage_postdate'];
$sql = "ALTER TABLE {$table} CHANGE COLUMN `postdate` `date_sent` DATETIME NOT NULL";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon['alter_usermessage_subject'];
$sql = "ALTER TABLE {$table} CHANGE COLUMN `subject` `subject` VARCHAR(255) NOT NULL";
$this->processResults($class,$description,$sql);
$description = $this->install->lexicon['alter_usermessage_messageread'];
$sql = "ALTER TABLE {$table} CHANGE COLUMN `messageread` `read` TINYINT(1) NOT NULL";
$this->processResults($class,$description,$sql);
