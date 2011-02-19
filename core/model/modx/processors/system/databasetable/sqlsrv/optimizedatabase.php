<?php
/**
 * SqlSrv database optimization queries
 *
 * Uses query from following discussion on defragmenting indexes
 * http://blog.sqlauthority.com/2008/03/04/sql-server-2005-a-simple-way-to-defragment-all-indexes-in-a-database-that-is-fragmented-above-a-declared-threshold/
 *
 * @package modx
 * @subpackage processors.system.databasetable
 */
if (!$modx->hasPermission('database')) return $modx->error->failure($modx->lexicon('permission_denied'));

$sql = file_get_contents(dirname(__FILE__) . '/defragment-indexes.sql');
$sql = str_replace('[[+dbname]]', $modx->escape($modx->config['database']), $sql);
$c = new xPDOCriteria($modx, $sql);
if($c->stmt->execute() === false) {
    return $modx->error->failure($modx->lexicon('optimize_table_err'));
}
$er = $c->stmt->errorInfo();
if($er) {
    $sqlstate_class = substr($er[0], 0, 2);
    $sqlstate_subclass = substr($er[0], 2);
    switch($sqlstate_class) {
        case '00':
            // success
            return $modx->error->success();
            break;
        case '01':
            // success with warning
            return $modx->error->success($er[2]);
            break;
        case '02':
            // no data found
        default:
            // error
            return $modx->error->failure($er[2]);
    }
}
return $modx->error->success();
