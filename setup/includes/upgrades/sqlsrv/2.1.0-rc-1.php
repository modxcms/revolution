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
