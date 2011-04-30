<?php
/**
 * MySQL database optimization queries
 *
 * @package modx
 * @subpackage processors.system.databasetable
 */
if (!$modx->hasPermission('database')) return $modx->error->failure($modx->lexicon('permission_denied'));

$stmt = $modx->query('SHOW TABLES');
if ($stmt && $stmt instanceof PDOStatement) {
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        if (!empty($row[0])) {
            $sql = 'OPTIMIZE TABLE '.$modx->escape($modx->getOption('dbname')).'.'.$modx->escape($row[0]);
            $modx->query($sql);
        }
    }
    $stmt->closeCursor();
} else {
    return $modx->error->failure($modx->lexicon('optimize_table_err'));
}
return $modx->error->success();
