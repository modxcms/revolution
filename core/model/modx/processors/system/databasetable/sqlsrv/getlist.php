<?php
/**
 * Gets a list of database tables
 *
 * SqlSrv-specific queries and results
 *
 * @package modx
 * @subpackage processors.system.databasetable
 */
if (!$modx->hasPermission('database')) return $modx->error->failure($modx->lexicon('permission_denied'));

$c = new xPDOCriteria($modx, "select [name] from sys.Tables where [type] = 'U' ORDER BY [name]");
$c->stmt->execute();
$table_names = $c->stmt->fetchAll(PDO::FETCH_COLUMN);

$dt = array();
foreach($table_names as $table_name) {
    $c = new xPDOCriteria($modx, "exec sp_spaceused " . $modx->escape($table_name));
    $c->stmt->execute();
    $row = $c->stmt->fetch(PDO::FETCH_ASSOC);
    $row['Name'] = $row['name'];
    $row['Rows'] = $row['rows'];

	/* calculations first */
	if ($modx->hasPermission('settings') && $row['name'] == $modx->getOption('table_prefix').'event_log' && $row['data'] + $row['unused']>0) {
		$row['Data_size'] = '<a href="javascript:;" onclick="truncate(\''.$row['name'].'\');" title="'.$modx->lexicon('truncate_table').'">'. $row['data'] . '</a>';
	} else {
		$row['Data_size'] = $row['data'];
	}

	/* now the non-calculated fields */
    $row['Reserved'] = $row['reserved'];
	$row['Data_length'] = $row['data'];
	if ($modx->hasPermission('settings') && $row['unused']>0) {
		$row['Data_free'] = '<a href="javascript:;" onclick="optimize(\''.$row['name'].'\');" title="'.$modx->lexicon('optimize_table').'">'.$row['unused'].'</a>';
	} else {
		$row['Data_free'] = $row['unused'];
	}
	$row['Index_length'] = $row['index_size'];
	$dt[] = $row;
}
