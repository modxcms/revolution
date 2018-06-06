<?php

namespace MODX\mysql;

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
                        'dbtype' => 'varchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'pk',
                    ],
                'name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'index' => 'index',
                    ],
                'description' =>
                    [
                        'dbtype' => 'tinytext',
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
                'SourceElements' =>
                    [
                        'class' => 'MODX\\Sources\\modMediaSourceElement',
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
            $time = microtime(true);
            $cache_alias_map = $context->getOption('cache_alias_map');
            $use_context_resource_table = $context->getOption('use_context_resource_table', null, 1);

            $tblResource = $context->xpdo->getTableName('modResource');
            $tblContextResource = $context->xpdo->getTableName('modContextResource');

            $resourceFields = ['id', 'parent', 'uri'];

            // we do not need to select uri if cache_alias_map is set to false
            if ($cache_alias_map == 0) {
                $resourceFields = ['id', 'parent'];
            }

            $resourceCols = $context->xpdo->getSelectColumns('modResource', 'r', '', $resourceFields);
            $contextKey = $context->get('key');

            $bindings = [];
            $sql = "SELECT {$resourceCols} FROM {$tblResource} `r` ";
            if ($use_context_resource_table) {
                $bindings = [$contextKey, $contextKey];
                $sql .= "FORCE INDEX (`cache_refresh_idx`) ";
                $sql .= "LEFT JOIN {$tblContextResource} `cr` ON `cr`.`context_key` = ? AND `r`.`id` = `cr`.`resource` ";
            }
            $sql .= "WHERE `r`.`deleted` = 0 "; //"AND `r`.`id` != `r`.`parent`";
            if ($use_context_resource_table) {
                $sql .= "AND (`r`.`context_key` = ? OR `cr`.`context_key` IS NOT NULL) ";
                $sql .= "GROUP BY `r`.`parent`, `r`.`menuindex`, `r`.`id`, `r`.`uri` ";
            } else {
                $bindings = [$contextKey];
                $sql .= "   AND `r`.`context_key` = ?";
            }

            $criteria = new xPDOCriteria($context->xpdo, $sql, $bindings, false);
            if ($criteria && $criteria->stmt && $criteria->stmt->execute()) {
                $stmt =& $criteria->stmt;
            }

            // output warning if query is too slow
            $time = ((microtime(true) - $time));
            if ($time >= 1.0 && $use_context_resource_table == 1) {
                $context->xpdo->log(MODX::LOG_LEVEL_WARN, "[modContext_mysql] Slow query detected. Consider to set 'use_context_resource_table' to false.");
            }
        }

        return $stmt;
    }


    public static function getWebLinkCacheMapStmt(&$context)
    {
        $stmt = false;
        if ($context instanceof modContext) {
            $time = microtime(true);
            $use_context_resource_table = $context->getOption('use_context_resource_table', null, 1);

            $tblResource = $context->xpdo->getTableName('modResource');
            $tblContextResource = $context->xpdo->getTableName('modContextResource');
            $resourceFields = ['id', 'content'];

            $resourceCols = $context->xpdo->getSelectColumns('modResource', 'r', '', $resourceFields);
            $contextKey = $context->get('key');

            $bindings = [];
            $sql = "SELECT {$resourceCols} ";
            $sql .= "FROM {$tblResource} `r` ";
            if ($use_context_resource_table) {
                $bindings = [$contextKey, $contextKey];
                $sql .= "LEFT JOIN {$tblContextResource} `cr` ";
                $sql .= "ON `cr`.`context_key` = ? AND `r`.`id` = `cr`.`resource` ";
            }
            $sql .= "WHERE `r`.`deleted` = 0 "; //"`r`.`id` != `r`.`parent` ";
            $sql .= "AND `r`.`class_key` = 'modWebLink' ";

            if ($use_context_resource_table) {
                $sql .= "AND (`r`.`context_key` = ? OR `cr`.`context_key` IS NOT NULL) ";
                $sql .= "GROUP BY `r`.`id`";
            } else {
                $bindings = [$contextKey];
                $sql .= "   AND `r`.`context_key` = ?";
            }

            $criteria = new xPDOCriteria($context->xpdo, $sql, $bindings, false);
            if ($criteria && $criteria->stmt && $criteria->stmt->execute()) {
                $stmt =& $criteria->stmt;
            }

            $time = ((microtime(true) - $time));
            if ($time >= 1.0 && $use_context_resource_table == 1) {
                $context->xpdo->log(MODX::LOG_LEVEL_WARN, "[modContext_mysql] Slow query detected. Consider to set 'use_context_resource_table' to false.");
            }
        }

        return $stmt;
    }
}
