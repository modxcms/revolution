<?php
/**
 * Gets a list of database tables
 *
 * @package modx
 * @subpackage processors.system.databasetable
 */
if (!$modx->hasPermission('database')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('system_info');

$c = new xPDOCriteria($modx, 'SHOW TABLE STATUS FROM `'.$modx->getOption('dbname').'`');
$c->stmt->execute();
$dt = array();
while ($row= $c->stmt->fetch(PDO::FETCH_ASSOC)) {
	/* calculations first */
	if ($modx->hasPermission('settings') && $row['Name'] == $modx->getOption('table_prefix').'event_log' && $row['Data_length'] + $row['Data_free']>0) {
		$row['Data_size'] = '<a href="javascript:;" onclick="truncate(\''.$row['Name'].'\');" title="'.$modx->lexicon('truncate_table').'">'. nicesize($row['Data_length'] + $row['Data_free']).'</a>';
	} else {
		$row['Data_size'] = nicesize($row['Data_length'] + $row['Data_free']);
	}
	$row['Effective_size'] = nicesize($row['Data_length'] - $row['Data_free']);
	$row['Total_size'] = nicesize($row['Index_length'] + $row['Data_length'] + $row['Data_free']);

	/* now the non-calculated fields */
	$row['Data_length'] = nicesize($row['Data_length']);
	if ($modx->hasPermission('settings') && $row['Data_free']>0) {
		$row['Data_free'] = '<a href="javascript:;" onclick="optimize(\''.$row['Name'].'\');" title="'.$modx->lexicon('optimize_table').'">'.nicesize($row['Data_free']).'</a>';
	} else {
		$row['Data_free'] = nicesize($row['Data_free']);
	}
	$row['Index_length'] = nicesize($row['Index_length']);
	$dt[] = $row;
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