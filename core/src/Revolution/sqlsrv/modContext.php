<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\Om\xPDOCriteria;
use xPDO\xPDO;

class modContext extends \MODX\Revolution\modContext
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'context',
        'extends' => 'MODX\\Revolution\\modAccessibleObject',
        'fields' =>
        array (
            'key' => NULL,
            'name' => NULL,
            'description' => NULL,
            'rank' => 0,
        ),
        'fieldMeta' =>
        array (
            'key' =>
            array (
                'dbtype' => 'nvarchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'index' => 'pk',
            ),
            'name' =>
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'index' => 'index',
            ),
            'description' =>
            array (
                'dbtype' => 'nvarchar',
                'precision' => '512',
                'phptype' => 'string',
            ),
            'rank' =>
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
        ),
        'indexes' =>
        array (
            'PRIMARY' =>
            array (
                'alias' => 'PRIMARY',
                'primary' => true,
                'unique' => true,
                'type' => 'BTREE',
                'columns' =>
                array (
                    'key' =>
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'name' =>
            array (
                'alias' => 'name',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' =>
                array (
                    'name' =>
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'rank' =>
            array (
                'alias' => 'rank',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' =>
                array (
                    'rank' =>
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
        'composites' =>
        array (
            'ContextResources' =>
            array (
                'class' => 'MODX\\Revolution\\modContextResource',
                'local' => 'key',
                'foreign' => 'context_key',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'ContextSettings' =>
            array (
                'class' => 'MODX\\Revolution\\modContextSetting',
                'local' => 'key',
                'foreign' => 'context_key',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'Acls' =>
            array (
                'class' => 'MODX\\Revolution\\modAccessContext',
                'local' => 'key',
                'foreign' => 'target',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
        ),
        'validation' =>
        array (
            'rules' =>
            array (
                'key' =>
                array (
                    'key' =>
                    array (
                        'type' => 'preg_match',
                        'rule' => '/^[a-zA-Z\\x7f-\\xff][a-zA-Z0-9\\x2d-\\x2f\\x7f-\\xff]*$/',
                        'message' => 'context_err_ns_key',
                    ),
                ),
            ),
        ),
    );

    public static function getResourceCacheMapStmt(&$context)
    {
        $stmt = false;
        if ($context instanceof modContext) {
            $tblResource= $context->xpdo->getTableName(\MODX\Revolution\modResource::class);
            $tblContextResource= $context->xpdo->getTableName(\MODX\Revolution\modContextResource::class);
            $resourceFields= array('id','parent','uri','menuindex');
            $resourceCols= $context->xpdo->getSelectColumns(\MODX\Revolution\modResource::class, 'r', '', $resourceFields);
            $bindings= array($context->get('key'), $context->get('key'));
            $sql = "SELECT {$resourceCols} FROM {$tblResource} [r] LEFT JOIN {$tblContextResource} [cr] ON [cr].[context_key] = ? AND [r].[id] = [cr].[resource] WHERE [r].[id] != [r].[parent] AND ([r].[context_key] = ? OR [cr].[context_key] IS NOT NULL) AND [r].[deleted] = 0 GROUP BY {$resourceCols} ORDER BY [r].[parent] ASC, [r].[menuindex] ASC";
            $criteria= new xPDOCriteria($context->xpdo, $sql, $bindings, false);
            if ($criteria && $criteria->stmt && $criteria->stmt->execute()) {
                $stmt= & $criteria->stmt;
            }
        }

        return $stmt;
    }

    public static function getWebLinkCacheMapStmt(&$context)
    {
        $stmt = false;
        if ($context instanceof modContext) {
            $tblResource = $context->xpdo->getTableName(\MODX\Revolution\modResource::class);
            $tblContextResource = $context->xpdo->getTableName(\MODX\Revolution\modContextResource::class);
            $resourceFields= array('id','content');
            $resourceCols= $context->xpdo->getSelectColumns(\MODX\Revolution\modResource::class, 'r', '', $resourceFields);
            $bindings = array($context->get('key'), $context->get('key'));
            $sql = "SELECT {$resourceCols} FROM {$tblResource} [r] LEFT JOIN {$tblContextResource} [cr] ON [cr].[context_key] = ? AND [r].[id] = [cr].[resource] WHERE [r].[id] != [r].[parent] AND [r].[class_key] = {$context->xpdo->quote(\MODX\Revolution\modWebLink::class)} AND ([r].[context_key] = ? OR [cr].[context_key] IS NOT NULL) AND [r].[deleted] = 0 GROUP BY [r].[id]";
            $criteria = new xPDOCriteria($context->xpdo, $sql, $bindings, false);
            if ($criteria && $criteria->stmt && $criteria->stmt->execute()) {
                $stmt =& $criteria->stmt;
            }
        }

        return $stmt;
    }
}
