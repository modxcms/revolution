<?php
/**
 * Specific upgrades for Revolution 2.0.0-beta-5
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

/* add unique index to modLexiconEntry */
$class = 'modLexiconEntry';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['add_index'],'uniqentry',$table);
$sql = "ALTER TABLE  {$table} ADD UNIQUE `uniqentry` (`name`,`topic`,`namespace`,`language`)";
$this->processResults($class, $description, $sql);
unset($class,$table,$sql,$description);


/* add city field to modUserProfile */
$class = 'modUserProfile';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['add_column'],'city',$table);
$sql = "ALTER TABLE {$table} ADD COLUMN `city` VARCHAR(255) NOT NULL DEFAULT '' AFTER `country`";
$this->processResults($class,$description,$sql);

/* adjust country field to modUserProfile */
$description = sprintf($this->install->lexicon['add_column'],'country',$table);
$sql = "ALTER TABLE {$table} CHANGE `country` `country` VARCHAR( 255 ) NOT NULL DEFAULT ''";
$this->processResults($class,$description,$sql);


/* add address field to modUserProfile */
$class = 'modUserProfile';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['add_column'],'address',$table);
$sql = "ALTER TABLE {$table} ADD COLUMN `address` TEXT NOT NULL DEFAULT '' AFTER `gender`";
$this->processResults($class,$description,$sql);

/* change session.id field to precision 40 */
$class = 'modSession';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['change_column'],'id varchar(32)','id varchar(40)',$table);
$sql = "ALTER TABLE {$table} CHANGE `id` `id` VARCHAR(40) NOT NULL";
$this->processResults($class,$description,$sql);
