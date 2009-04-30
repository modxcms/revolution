<?php
/**
 * Handles all upgrades related to Revolution 2.0.0-alpha-1
 *
 * @package setup
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
$description = 'Removed full-text index `content_ft_idx`.';
$removedOldFullTextIndex = $this->processResults($class,$description,$sql);

$sql = "ALTER TABLE {$table} ADD COLUMN `content_type` INT(11) unsigned NOT NULL DEFAULT 0 ";
$description = 'Added `content_type` column.';
$this->processResults($class,$description,$sql);

if ($removedOldFullTextIndex) {
    $sql = "ALTER TABLE {$table} ADD FULLTEXT INDEX `content_ft_idx` (`pagetitle`, `longtitle`, `description`, `introtext`, `content`)";
    $description = 'Added new `content_ft_idx` full-text index on the fields `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
    $this->processResults($class,$description,$sql);
}


$class = 'modUserGroup';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added new column `parent`.';
$sql = "ALTER TABLE {$table} ADD COLUMN `parent` INT(11) unsigned NOT NULL DEFAULT 0";
$this->processResults($class,$description,$sql);

$description = 'Added new index on `parent`.';
$sql = "ALTER TABLE {$table} ADD INDEX `parent` (`parent`)";
$this->processResults($class,$description,$sql);

$class = 'modUserGroupMember';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added new column `role`.';
$sql = "ALTER TABLE {$table} ADD COLUMN `role` INT(10) unsigned NOT NULL DEFAULT 0";
$this->processResults($class,$description,$sql);

$description = 'Added new index on `role`.';
$sql = "ALTER TABLE {$table} ADD INDEX `role` (`role`)";
$this->processResults($class,$description,$sql);

$class = 'modUser';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added cachepwd field missing in early Revolution releases';
$sql = "ALTER TABLE {$table} ADD COLUMN `cachepwd` VARCHAR(100) NOT NULL DEFAULT '' AFTER `password`";
$this->processResults($class,$description,$sql);


$class = 'modActiveUser';
$table = $this->install->xpdo->getTableName($class);
$description = 'Modified modActiveUser `action` field to allow longer action labels';
$sql = "ALTER TABLE {$table} MODIFY COLUMN `action` VARCHAR(255)";
$this->processResults($class,$description,$sql);

$class = 'modAction';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added modAction `lang_foci` field.';
$sql = "ALTER TABLE {$table} ADD COLUMN `lang_foci` TEXT AFTER `haslayout`";
$this->processResults($class,$description,$sql);


$class = 'modSystemSetting';
$table = $this->install->xpdo->getTableName($class);
$description = 'Changed modSystemSetting `setting_name` field to `key`.';
$sql = "ALTER TABLE {$table} CHANGE COLUMN `setting_name` `key` VARCHAR(50) NOT NULL";
$this->processResults($class,$description,$sql);
$description = 'Changed modSystemSetting `setting_value` field to `value`.';
$sql = "ALTER TABLE {$table} CHANGE COLUMN `setting_value` `value` TEXT NOT NULL";
$this->processResults($class,$description,$sql);
$description = 'Added modSystemSetting `xtype`.';
$sql = "ALTER TABLE {$table} ADD COLUMN `xtype` VARCHAR(75) NOT NULL DEFAULT 'textfield'";
$this->processResults($class,$description,$sql);


$class = 'modContextSetting';
$table = $this->install->xpdo->getTableName($class);
$description = 'Added modContextSetting `xtype`.';
$sql = "ALTER TABLE {$table} ADD COLUMN `xtype` VARCHAR(75) NOT NULL DEFAULT 'textfield'";
$this->processResults($class,$description,$sql);


$class = 'modUserSetting';
$table = $this->install->xpdo->getTableName($class);
$description = 'Changed modUserSetting `setting_name` field to `key`.';
$sql = "ALTER TABLE {$table} CHANGE COLUMN `setting_name` `key` VARCHAR(50) NOT NULL";
$this->processResults($class,$description,$sql);
$description = 'Changed modUserSetting `setting_value` field to `value`.';
$sql = "ALTER TABLE {$table} CHANGE COLUMN `setting_value` `value` TEXT NOT NULL";
$this->processResults($class,$description,$sql);
$description = 'Added modUserSetting `xtype`.';
$sql = "ALTER TABLE {$table} ADD COLUMN `xtype` VARCHAR(75) NOT NULL DEFAULT 'textfield'";
$this->processResults($class,$description,$sql);


$class = 'modManagerLog';
$table = $this->install->xpdo->getTableName($class);
$description = 'Changed modManagerLog `class_key` field to `classKey`.';
$sql = "ALTER TABLE {$table} CHANGE COLUMN `class_key` `classKey` VARCHAR(100) NOT NULL";
$this->processResults($class,$description,$sql);

$class = 'modUserMessage';
$table = $this->install->xpdo->getTableName($class);
$description = 'Changed modUserMessage `postdate` field from an INT to a DATETIME and to name `date_sent`.';
$sql = "ALTER TABLE {$table} CHANGE COLUMN `postdate` `date_sent` DATETIME NOT NULL";
$this->processResults($class,$description,$sql);
$description = 'Changed modUserMessage `subject` field from VARCHAR(60) to VARCHAR(255).';
$sql = "ALTER TABLE {$table} CHANGE COLUMN `subject` `subject` VARCHAR(255) NOT NULL";
$this->processResults($class,$description,$sql);
$description = 'Changed modUserMessage `messageread` field to `read`.';
$sql = "ALTER TABLE {$table} CHANGE COLUMN `messageread` `read` TINYINT(1) NOT NULL";
$this->processResults($class,$description,$sql);
