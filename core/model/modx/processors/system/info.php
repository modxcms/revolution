<?php
/**
 * Removes locks on all objects
 *
 * @package modx
 * @subpackage processors.system
 */
if (!$modx->hasPermission('view_sysinfo')) return $modx->error->failure($modx->lexicon('permission_denied'));

$data = array();

$modx->getVersionData();
$serverOffset = $modx->getOption('server_offset_time',null,0) * 60 * 60;

/* general */
$data['modx_version'] = $modx->version['full_appname'];
$data['code_name'] = $modx->version['code_name'];
$data['servertime'] = strftime('%I:%M:%S %p', time());
$data['localtime'] = strftime('%I:%M:%S %p', time()+$serverOffset);
$data['serveroffset'] = $serverOffset / (60*60);

/* database info */
$data['database_type'] = $modx->getOption('dbtype');
$stmt= $modx->query("SELECT VERSION()");
if ($stmt) {
    $result= $stmt->fetch(PDO::FETCH_COLUMN);
    $stmt->closeCursor();
} else {
    $result='-';
}
$data['database_version'] = $result;
$data['database_charset'] = $modx->getOption('charset');
$data['database_name'] = str_replace('`','',$modx->getOption('dbname'));
$data['database_server'] = $modx->getOption('host');
$data['now'] = strftime('%b %d, %Y %I:%M %p',time());
$data['table_prefix'] = $modx->getOption('table_prefix');

return $modx->error->success('',$data);