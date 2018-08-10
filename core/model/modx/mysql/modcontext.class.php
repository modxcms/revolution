<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../modcontext.class.php');
/**
 * @package modx
 * @subpackage mysql
 */
class modContext_mysql extends modContext {
    public static function getResourceCacheMapStmt(&$context) {
        $stmt = false;
        if ($context instanceof modContext) {
            $time=microtime(true);
            $cache_alias_map = $context->getOption('cache_alias_map');
            $use_context_resource_table = $context->getOption('use_context_resource_table',null,1);

            $tblResource= $context->xpdo->getTableName('modResource');
            $tblContextResource= $context->xpdo->getTableName('modContextResource');

            $resourceFields= array('id','parent','uri');

            // we do not need to select uri if cache_alias_map is set to false
            if ($cache_alias_map == 0) {
                $resourceFields = array('id','parent');
            }

            $resourceCols= $context->xpdo->getSelectColumns('modResource', 'r', '', $resourceFields);
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
            if ($criteria && $criteria->stmt && $criteria->stmt->execute()) {
                $stmt =& $criteria->stmt;
            }

            // output warning if query is too slow
            $time = ((microtime(true)-$time));
            if ($time >= 1.0 && $use_context_resource_table==1) {
                $context->xpdo->log(modX::LOG_LEVEL_WARN,"[modContext_mysql] Slow query detected. Consider to set 'use_context_resource_table' to false.");
            }
        }
        return $stmt;
    }

    public static function getWebLinkCacheMapStmt(&$context) {
        $stmt = false;
        if ($context instanceof modContext) {
            $time=microtime(true);
            $use_context_resource_table = $context->getOption('use_context_resource_table',null,1);

            $tblResource = $context->xpdo->getTableName('modResource');
            $tblContextResource = $context->xpdo->getTableName('modContextResource');
            $resourceFields= array('id','content');

            $resourceCols= $context->xpdo->getSelectColumns('modResource', 'r', '', $resourceFields);
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
            $sql .= "AND `r`.`class_key` = 'modWebLink' ";

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
