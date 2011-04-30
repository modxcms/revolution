<?php
/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
if (!$modx->hasPermission('database_truncate')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('system_info');

if (empty($scriptProperties['t'])) return $modx->error->failure($modx->lexicon('truncate_table_err'));

$dbtype_processor = $modx->config['dbtype'] . '/truncate.php';
$dbtype_processor_path = dirname(__FILE__) . '/' . $dbtype_processor;
if(file_exists($dbtype_processor_path)) {
    $return = include $dbtype_processor_path;
} else {
    return $modx->error->failure($this->modx->lexicon('truncate_table_err'));
}

/* log manager action if success */
if($modx->error->status) $modx->logManagerAction('database_truncate','',0);

return $return;
