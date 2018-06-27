<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
require_once (dirname(__DIR__) . '/modcontext.class.php');
/**
 * @package modx
 * @subpackage sqlsrv
 */
class modContext_sqlsrv extends modContext {
    public static function getResourceCacheMapStmt(&$context) {
        $stmt = false;
        if ($context instanceof modContext) {
            $tblResource= $context->xpdo->getTableName('modResource');
            $tblContextResource= $context->xpdo->getTableName('modContextResource');
            $resourceFields= array('id','parent','uri','menuindex');
            $resourceCols= $context->xpdo->getSelectColumns('modResource', 'r', '', $resourceFields);
            $bindings= array($context->get('key'), $context->get('key'));
            $sql = "SELECT {$resourceCols} FROM {$tblResource} [r] LEFT JOIN {$tblContextResource} [cr] ON [cr].[context_key] = ? AND [r].[id] = [cr].[resource] WHERE [r].[id] != [r].[parent] AND ([r].[context_key] = ? OR [cr].[context_key] IS NOT NULL) AND [r].[deleted] = 0 GROUP BY {$resourceCols} ORDER BY [r].[parent] ASC, [r].[menuindex] ASC";
            $criteria= new xPDOCriteria($context->xpdo, $sql, $bindings, false);
            if ($criteria && $criteria->stmt && $criteria->stmt->execute()) {
                $stmt= & $criteria->stmt;
            }
        }
        return $stmt;
    }

    public static function getWebLinkCacheMapStmt(&$context) {
        $stmt = false;
        if ($context instanceof modContext) {
            $tblResource = $context->xpdo->getTableName('modResource');
            $tblContextResource = $context->xpdo->getTableName('modContextResource');
            $resourceFields= array('id','content');
            $resourceCols= $context->xpdo->getSelectColumns('modResource', 'r', '', $resourceFields);
            $bindings = array($context->get('key'), $context->get('key'));
            $sql = "SELECT {$resourceCols} FROM {$tblResource} [r] LEFT JOIN {$tblContextResource} [cr] ON [cr].[context_key] = ? AND [r].[id] = [cr].[resource] WHERE [r].[id] != [r].[parent] AND [r].[class_key] = 'modWebLink' AND ([r].[context_key] = ? OR [cr].[context_key] IS NOT NULL) AND [r].[deleted] = 0 GROUP BY [r].[id]";
            $criteria = new xPDOCriteria($context->xpdo, $sql, $bindings, false);
            if ($criteria && $criteria->stmt && $criteria->stmt->execute()) {
                $stmt =& $criteria->stmt;
            }
        }
        return $stmt;
    }
}
