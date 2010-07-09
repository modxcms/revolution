<?php
/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
if (!$modx->hasPermission('database')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('system_info');

if (empty($scriptProperties['t'])) return $modx->error->failure($modx->lexicon('optimize_table_err'));

$sql = 'OPTIMIZE TABLE `'.$modx->getOption('dbname').'`.'.$scriptProperties['t'];
if ($modx->exec($sql) === false) {
    return $modx->error->failure($modx->lexicon('optimize_table_err'));
}

/* log manager action */
$modx->logManagerAction('database_optimize','table',$scriptProperties['t']);

return $modx->error->success();