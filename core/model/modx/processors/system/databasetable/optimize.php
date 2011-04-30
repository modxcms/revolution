<?php
/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
if (!$modx->hasPermission('database')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('system_info');

if (empty($scriptProperties['t'])) return $modx->error->failure($modx->lexicon('optimize_table_err'));

$dbtype_processor = $modx->config['dbtype'] . '/optimize.php';
$dbtype_processor_path = dirname(__FILE__) . '/' . $dbtype_processor;
$return = false;
if(file_exists($dbtype_processor_path)) {
    $return = include $dbtype_processor_path;
} else {
    return $modx->error->failure($this->modx->lexicon('optimize_table_err'));
}

/* log manager action if success */
if($modx->error->status) $modx->logManagerAction('database_optimize','table',$scriptProperties['t']);

return $return;
