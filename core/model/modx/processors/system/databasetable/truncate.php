<?php
/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
if (!$modx->hasPermission('database_truncate')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('system_info');

if (empty($scriptProperties['t'])) return $modx->error->failure($modx->lexicon('truncate_table_err'));

$sql = 'TRUNCATE TABLE `'.$modx->getOption('dbname').'`.'.$scriptProperties['t'];
if ($modx->exec($sql) === false) {
    return $modx->error->failure($modx->lexicon('truncate_table_err'));
}

/* log manager action */
$modx->logManagerAction('database_truncate','',0);

return $modx->error->success();