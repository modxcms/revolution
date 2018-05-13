<?php

namespace MODX\sqlsrv;

use xPDO\Om\xPDOCriteria;

class modContext extends \MODX\modContext
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'context',
        'extends' => 'MODX\\modAccessibleObject',
        'fields' =>
            [
                'key' => null,
                'name' => null,
                'description' => null,
                'rank' => 0,
            ],
        'fieldMeta' =>
            [
                'key' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'pk',
                    ],
                'name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'index' => 'index',
                    ],
                'description' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '512',
                        'phptype' => 'string',
                    ],
                'rank' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '11',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
            ],
        'indexes' =>
            [
                'PRIMARY' =>
                    [
                        'alias' => 'PRIMARY',
                        'primary' => true,
                        'unique' => true,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'key' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'name' =>
                    [
                        'alias' => 'name',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'name' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'rank' =>
                    [
                        'alias' => 'rank',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'rank' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'composites' =>
            [
                'ContextResources' =>
                    [
                        'class' => 'MODX\\modContextResource',
                        'local' => 'key',
                        'foreign' => 'context_key',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'ContextSettings' =>
                    [
                        'class' => 'MODX\\modContextSetting',
                        'local' => 'key',
                        'foreign' => 'context_key',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'Acls' =>
                    [
                        'class' => 'MODX\\modAccessContext',
                        'local' => 'key',
                        'foreign' => 'target',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
            ],
        'validation' =>
            [
                'rules' =>
                    [
                        'key' =>
                            [
                                'key' =>
                                    [
                                        'type' => 'preg_match',
                                        'rule' => '/^[a-zA-Z\\x7f-\\xff][a-zA-Z0-9\\x2d-\\x2f\\x7f-\\xff]*$/',
                                        'message' => 'context_err_ns_key',
                                    ],
                            ],
                    ],
            ],
    ];


    public static function getResourceCacheMapStmt(&$context)
    {
        $stmt = false;
        if ($context instanceof modContext) {
            $tblResource = $context->xpdo->getTableName('modResource');
            $tblContextResource = $context->xpdo->getTableName('modContextResource');
            $resourceFields = ['id', 'parent', 'uri', 'menuindex'];
            $resourceCols = $context->xpdo->getSelectColumns('modResource', 'r', '', $resourceFields);
            $bindings = [$context->get('key'), $context->get('key')];
            $sql = "SELECT {$resourceCols} FROM {$tblResource} [r] LEFT JOIN {$tblContextResource} [cr] ON [cr].[context_key] = ? AND [r].[id] = [cr].[resource] WHERE [r].[id] != [r].[parent] AND ([r].[context_key] = ? OR [cr].[context_key] IS NOT NULL) AND [r].[deleted] = 0 GROUP BY {$resourceCols} ORDER BY [r].[parent] ASC, [r].[menuindex] ASC";
            $criteria = new xPDOCriteria($context->xpdo, $sql, $bindings, false);
            if ($criteria && $criteria->stmt && $criteria->stmt->execute()) {
                $stmt = &$criteria->stmt;
            }
        }

        return $stmt;
    }


    public static function getWebLinkCacheMapStmt(&$context)
    {
        $stmt = false;
        if ($context instanceof modContext) {
            $tblResource = $context->xpdo->getTableName('modResource');
            $tblContextResource = $context->xpdo->getTableName('modContextResource');
            $resourceFields = ['id', 'content'];
            $resourceCols = $context->xpdo->getSelectColumns('modResource', 'r', '', $resourceFields);
            $bindings = [$context->get('key'), $context->get('key')];
            $sql = "SELECT {$resourceCols} FROM {$tblResource} [r] LEFT JOIN {$tblContextResource} [cr] ON [cr].[context_key] = ? AND [r].[id] = [cr].[resource] WHERE [r].[id] != [r].[parent] AND [r].[class_key] = 'modWebLink' AND ([r].[context_key] = ? OR [cr].[context_key] IS NOT NULL) AND [r].[deleted] = 0 GROUP BY [r].[id]";
            $criteria = new xPDOCriteria($context->xpdo, $sql, $bindings, false);
            if ($criteria && $criteria->stmt && $criteria->stmt->execute()) {
                $stmt =& $criteria->stmt;
            }
        }

        return $stmt;
    }
}
