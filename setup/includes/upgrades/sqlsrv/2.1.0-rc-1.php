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
$sql = "ALTER TABLE {$table} ADD COLUMN {$modx->escape('uri')} NVARCHAR(1000) NULL AFTER {$modx->escape('content_type')}";
$this->processResults($class,$description,$sql);

$sql = "ALTER TABLE {$table} ADD INDEX [uri] ([uri](1000))";
$modx->exec($sql);

$description = $this->install->lexicon('add_column',array('column' => 'uri_override','table' => $table));
$sql = "ALTER TABLE {$table} ADD {$modx->escape('uri_override')} BIT NOT NULL DEFAULT 0 AFTER {$modx->escape('uri')}";
$this->processResults($class,$description,$sql);

$sql = "ALTER TABLE {$table} ADD INDEX [uri_override] ([uri_override])";
$modx->exec($sql);
