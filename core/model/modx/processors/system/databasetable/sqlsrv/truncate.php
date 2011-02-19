<?php
/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
if (!$modx->hasPermission('database_truncate')) return $modx->error->failure($modx->lexicon('permission_denied'));

$sql = 'TRUNCATE TABLE '.$modx->escape($scriptProperties['t']);
if ($modx->exec($sql) === false) {
    return $modx->error->failure($modx->lexicon('truncate_table_err'));
}
return $modx->error->success();
