<?php
/**
 * Specific upgrades for Revolution 2.1.0-rc-1 on sqlsrv.
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


/* add uri field and index to modResource */
$class = 'modResource';
$table = $modx->getTableName($class);

$description = $this->install->lexicon('add_column',array('column' => 'uri','table' => $table));
$sql = "ALTER TABLE {$table} ADD [uri] NVARCHAR(1000) NULL"; // AFTER {$modx->escape('content_type')}
$uriAdded = $this->processResults($class,$description,$sql);

$sql = "CREATE INDEX [uri] ON {$table} ([uri])";
$modx->exec($sql);

$description = $this->install->lexicon('add_column',array('column' => 'uri_override','table' => $table));
$sql = "ALTER TABLE {$table} ADD [uri_override] BIT NOT NULL DEFAULT 0"; // AFTER {$modx->escape('uri')}
$this->processResults($class,$description,$sql);

$sql = "CREATE INDEX [uri_override] ON {$table} ([uri_override])";
$modx->exec($sql);

if ($uriAdded && $modx->getOption('friendly_urls')) {
    $modx->call('modResource', 'refreshURIs', array(&$modx));
}

/* add hash_class and salt to modUser */
$class = 'modUser';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'hash_class','table' => $table));
$sql = "ALTER TABLE {$table} ADD {$modx->escape('hash_class')} NVARCHAR(100) NOT NULL DEFAULT 'hashing.modPBKDF2'"; // AFTER {$modx->escape('remote_data')}
$hashClassAdded = $this->processResults($class,$description,$sql);

$description = $this->install->lexicon('add_column',array('column' => 'salt','table' => $table));
$sql = "ALTER TABLE {$table} ADD {$modx->escape('salt')} NVARCHAR(100) NOT NULL DEFAULT ''"; // AFTER {$modx->escape('hash_class')}
$this->processResults($class,$description,$sql);

if ($hashClassAdded) {
    $modx->exec("UPDATE {$table} SET {$modx->escape('hash_class')} = 'hashing.modMD5'");
}

/* remove haskeywords column in modResource */
$modx->getManager();

$class = 'modResource';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('drop_column',array('column' => 'haskeywords', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'removeField'), array($class, 'haskeywords'));

/* remove hasmetatags column in modResource */
$description = $this->install->lexicon('drop_column',array('column' => 'hasmetatags', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'removeField'), array($class, 'hasmetatags'));

/* remove modUserProfile.role column */
$class = 'modUserProfile';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('drop_column', array('column' => 'role', 'table' => $table));
$this->processResults($class, $description, array($modx->manager, 'removeField'), array($class, 'role'));

/* add description field to modUserGroup */
$class = 'modUserGroup';
$table = $modx->getTableName($class);
$description = $this->install->lexicon('add_column',array('column' => 'description','table' => $table));
$sql = "ALTER TABLE {$table} ADD [description] NVARCHAR(MAX) NULL";
$this->processResults($class,$description,$sql);