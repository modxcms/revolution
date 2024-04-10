<?php
namespace MODX\Revolution\mysql;

use MODX\Revolution\modX;
use xPDO\Om\xPDOCriteria;

class modContext extends \MODX\Revolution\modContext
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'context',
        'extends' => 'MODX\\Revolution\\modAccessibleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
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
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'index' => 'pk',
            ),
            'name' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'index' => 'index',
            ),
            'description' => 
            array (
                'dbtype' => 'tinytext',
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
            'SourceElements' => 
            array (
                'class' => 'MODX\\Revolution\\Sources\\modMediaSourceElement',
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
        if ($context instanceof \MODX\Revolution\modContext) {
            $time=microtime(true);
            $cache_alias_map = $context->getOption('cache_alias_map');
            $use_context_resource_table = $context->getOption('use_context_resource_table',null,1);

            $tblResource= $context->xpdo->getTableName(\MODX\Revolution\modResource::class);
            $tblContextResource= $context->xpdo->getTableName(\MODX\Revolution\modContextResource::class);

            $resourceFields= array('id','parent','uri');

            // we do not need to select uri if cache_alias_map is set to false
            if ($cache_alias_map == 0) {
                $resourceFields = array('id','parent');
            }

            $resourceCols= $context->xpdo->getSelectColumns(\MODX\Revolution\modResource::class, 'r', '', $resourceFields);
            $contextKey = $context->get('key');

            $bindings = array();
            $sql  = "SELECT {$resourceCols} FROM {$tblResource} `r` ";
            if ($use_context_resource_table) {
                $bindings = array($contextKey, $contextKey);
                $sql .= "FORCE INDEX (`cache_refresh_idx`) ";
                $sql .= "LEFT JOIN {$tblContextResource} `cr` ON `cr`.`context_key` = ? AND `r`.`id` = `cr`.`resource` ";
            }
            $sql .= "WHERE `r`.`deleted` = 0 "; //"AND `r`.`id` != `r`.`parent`";
            if ($use_context_resource_table) {
                $sql .= "AND (`r`.`context_key` = ? OR `cr`.`context_key` IS NOT NULL) ";
                $sql .= "GROUP BY `r`.`parent`, `r`.`menuindex`, `r`.`id`, `r`.`uri` ";
            } else {
                $bindings = array($contextKey);
                $sql .= "   AND `r`.`context_key` = ?";
            }

            $criteria = new xPDOCriteria($context->xpdo, $sql, $bindings, false);
            if ($criteria && $criteria->stmt) {
                if ($criteria->stmt->execute()) {
                    $stmt =& $criteria->stmt;
                }

                if ($criteria->stmt->errorCode() !== '00000') {
                    $context->xpdo->log(modX::LOG_LEVEL_ERROR, '[modContext_mysql] Encountered error loading resources for cache map generation: ' . $criteria->stmt->errorCode() . ' // ' . print_r($criteria->stmt->errorInfo(), true));
                }
            }

            // output warning if query is too slow
            $time = ((microtime(true)-$time));
            if ($time >= 1.0 && $use_context_resource_table==1) {
                $context->xpdo->log(modX::LOG_LEVEL_WARN,"[modContext_mysql] Slow query detected. Consider to set 'use_context_resource_table' to false.");
            }
        }
        return $stmt;
    }

    public static function getWebLinkCacheMapStmt(&$context)
    {
        $stmt = false;
        if ($context instanceof \MODX\Revolution\modContext) {
            $time=microtime(true);
            $use_context_resource_table = $context->getOption('use_context_resource_table',null,1);

            $tblResource = $context->xpdo->getTableName(\MODX\Revolution\modResource::class);
            $tblContextResource = $context->xpdo->getTableName(\MODX\Revolution\modContextResource::class);
            $resourceFields= array('id','content');

            $resourceCols= $context->xpdo->getSelectColumns(\MODX\Revolution\modResource::class, 'r', '', $resourceFields);
            $contextKey = $context->get('key');

            $bindings = array();
            $sql  = "SELECT {$resourceCols} ";
            $sql .= "FROM {$tblResource} `r` ";
            if ($use_context_resource_table) {
                $bindings = array($contextKey, $contextKey);
                $sql .= "LEFT JOIN {$tblContextResource} `cr` ";
                $sql .= "ON `cr`.`context_key` = ? AND `r`.`id` = `cr`.`resource` ";
            }
            $sql .= "WHERE `r`.`deleted` = 0 "; //"`r`.`id` != `r`.`parent` ";
            $sql .= "AND `r`.`class_key` = {$context->xpdo->quote(\MODX\Revolution\modWebLink::class)} ";

            if ($use_context_resource_table) {
                $sql .= "AND (`r`.`context_key` = ? OR `cr`.`context_key` IS NOT NULL) ";
                $sql .= "GROUP BY `r`.`id`";
            } else {
                $bindings = array($contextKey);
                $sql .= "   AND `r`.`context_key` = ?";
            }

            $criteria = new xPDOCriteria($context->xpdo, $sql, $bindings, false);
            if ($criteria && $criteria->stmt && $criteria->stmt->execute()) {
                $stmt =& $criteria->stmt;
            }

            $time = ((microtime(true)-$time));
            if ($time >= 1.0 && $use_context_resource_table==1) {
                $context->xpdo->log(modX::LOG_LEVEL_WARN,"[modContext_mysql] Slow query detected. Consider to set 'use_context_resource_table' to false.");
            }
        }
        return $stmt;
    }
}
