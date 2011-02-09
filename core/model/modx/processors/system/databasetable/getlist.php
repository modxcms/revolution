<?php
/**
 * Gets a list of database tables
 *
 * @package modx
 * @subpackage processors.system.databasetable
 */
if (!$modx->hasPermission('database')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('system_info');

$dt = array();
$dbtype_processor = dirname(__FILE__) . '/' . $modx->config['dbtype'] . '/getlist.php';
if(file_exists($dbtype_processor)) {
    include $dbtype_processor;
}

return $this->outputArray($dt);

function nicesize($size) {
	if (!isset($size) || !is_numeric($size) || $size == 0) return '0 B';
	$a = array('B','KB','MB','GB','TB','PB');
	$pos = 0;
	while ($size >= 1024) {
		   $size /= 1024;
		   $pos++;
	}
	return $size == 0 ? '-' : round($size,2).' '.$a[$pos];
}