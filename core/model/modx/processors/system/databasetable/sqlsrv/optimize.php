<?php
/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
if (!$modx->hasPermission('database')) return $modx->error->failure($modx->lexicon('permission_denied'));

$sql = 'ALTER INDEX ALL ON ' . $modx->escape($scriptProperties['t']) . ' REBUILD';
// $sql = 'ALTER INDEX ALL ON ' . $modx->escape($scriptProperties['t']) . ' REORGANIZE';

if ($modx->exec($sql) === false) {
    return $modx->error->failure($modx->lexicon('optimize_table_err'));
}
return $modx->error->success();