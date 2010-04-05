<?php
/**
 * Specific upgrades for Revolution 2.0.0-rc-1
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

/* change modResource.context_key to have default of 'web' */
$class = 'modResource';
$table = $this->install->xpdo->getTableName($class);
$description = sprintf($this->install->lexicon['change_column'],'context_key web','context_key web',$table);
$sql = "ALTER TABLE {$table} CHANGE  `context_key` `context_key` VARCHAR(100) NOT NULL DEFAULT 'web'";
$this->processResults($class,$description,$sql);
