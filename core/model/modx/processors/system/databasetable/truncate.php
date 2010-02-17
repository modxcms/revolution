<?php
/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
if (!$modx->hasPermission('database_truncate')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('system_info');

if (empty($_POST['t'])) return $modx->error->failure($modx->lexicon('truncate_table_err'));

$sql = 'TRUNCATE TABLE `'.$modx->getOption('dbname').'`.'.$_POST['t'];
if ($modx->exec($sql) === false) {
    return $modx->error->failure($modx->lexicon('truncate_table_err'));
}

/* log manager action */
$modx->logManagerAction('database_truncate','',0);

return $modx->error->success();