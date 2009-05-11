<?php
/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
$modx->lexicon->load('system_info');

if (!$modx->hasPermission('database')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

if ($_POST['t'] == '' || !isset($_POST['t'])) {
    return $modx->error->failure($modx->lexicon('optimize_table_err'));
}

$sql = 'OPTIMIZE TABLE `'.$modx->getOption('dbname').'`.'.$_POST['t'];
if ($modx->exec($sql) === false) {
    return $modx->error->failure($modx->lexicon('optimize_table_err'));
}

/* log manager action */
$modx->logManagerAction('database_optimize','',0);

return $modx->error->success();