<?php
/**
 * @package modx
 * @subpackage processors.system.databasetable
 */
if (!$modx->hasPermission('database')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('system_info');

$stmt = $modx->query('SHOW TABLES');
if ($stmt && $stmt instanceof PDOStatement) {
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        if (!empty($row[0])) {
            $sql = 'OPTIMIZE TABLE `'.$modx->getOption('dbname').'`.`'.$row[0].'`';
            $modx->query($sql);
        }
    }
    $stmt->closeCursor();
} else {
    return $modx->error->failure($modx->lexicon('optimize_table_err'));
}

/* log manager action */
$modx->logManagerAction('database_optimize','database',0);

return $modx->error->success();