<?php
/*
 * Copyright 2006-2010 by  Jason Coward <xpdo@opengeek.com>
 *
 * This file is part of xPDO.
 *
 * xPDO is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * xPDO is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * xPDO; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * The sqlsrv implementation of xPDOQuery.
 *
 * @package xpdo
 * @subpackage om.sqlsrv
 */

/** Include the base {@see xPDOQuery} class */
include_once (dirname(dirname(__FILE__)) . '/xpdoquery.class.php');

/**
 * An implementation of xPDOQuery for the sqlsrv database driver.
 *
 * @package xpdo
 * @subpackage om.sqlsrv
 */
class xPDOQuery_sqlsrv extends xPDOQuery {
    public function __construct(& $xpdo, $class, $criteria= null) {
        parent :: __construct($xpdo, $class, $criteria);
        $this->query['top']= 0;
    }

    public function construct() {
        $constructed= false;
        $this->bindings= array ();
        $command= strtoupper($this->query['command']);
        $sql= $this->query['command'] . ' ';
        $limit= !empty($this->query['limit']) ? intval($this->query['limit']) : 0;
        $offset= !empty($this->query['offset']) ? intval($this->query['offset']) : 0;
        if ($command == 'SELECT') {
        	$sql.= !empty($this->query['distinct']) ? $this->query['distinct'] . ' ' : '';
        	if (!empty($limit) && empty($offset)) {
        		$this->query['top'] = $limit;
        	}
        	$sql.= $this->query['top'] > 0 ? 'TOP ' . $this->query['top'] . ' ' : '';
            $columns= array ();
            if (empty ($this->query['columns'])) {
                $this->select('*');
            }
            $ignorealias= isset ($this->query['columns'][0]);
            foreach ($this->query['columns'] as $alias => $column) {
                $column= $this->xpdo->escape(trim($column));
                if (!$ignorealias && $alias !== $column) {
                    $alias = $this->xpdo->escape($alias);
                    $columns[]= "{$column} AS {$alias}";
                } else {
                    $columns[]= "{$column}";
                }
            }
            $sql.= implode(', ', $columns);
            $sql.= ' ';
        }
        $sql.= 'FROM ';
        $tables= array ();
        foreach ($this->query['from']['tables'] as $table) {
            if ($command != 'SELECT') {
                $tables[]= $this->xpdo->escape($table['table']);
            } else {
                $tables[]= $this->xpdo->escape($table['table']) . ' AS ' . $this->xpdo->escape($table['alias']);
            }
        }
        $sql.= $this->query['from']['tables'] ? implode(', ', $tables) . ' ' : '';
        if (!empty ($this->query['from']['joins'])) {
            foreach ($this->query['from']['joins'] as $join) {
                $sql.= $join['type'] . ' ' . $this->xpdo->escape($join['table']) . ' AS ' . $this->xpdo->escape($join['alias']) . ' ';
                if (!empty ($join['conditions'])) {
                    $sql.= 'ON ';
                    $sql.= $this->buildConditionalClause($join['conditions']);
                    $sql.= ' ';
                }
            }
        }
        if (!empty ($this->query['where'])) {
            if ($where= $this->buildConditionalClause($this->query['where'])) {
                $sql.= 'WHERE ' . $where . ' ';
            }
        }
        if ($command == 'SELECT' && !empty ($this->query['groupby'])) {
            $groupby= reset($this->query['groupby']);
            $sql.= 'GROUP BY ';
            $sql.= $groupby['column'];
            if ($groupby['direction']) $sql.= ' ' . $groupby['direction'];
            while ($groupby= next($this->query['groupby'])) {
                $sql.= ', ';
                $sql.= $groupby['column'];
                if ($groupby['direction']) $sql.= ' ' . $groupby['direction'];
            }
            $sql.= ' ';
        }
        if (!empty ($this->query['having'])) {
            $sql.= 'HAVING ';
            $sql.= $this->buildConditionalClause($this->query['having']);
            $sql.= ' ';
        }
        $orderBySql = '';
        if ($command == 'SELECT' && !empty ($this->query['sortby'])) {
            $sortby= reset($this->query['sortby']);
            $orderBySql= 'ORDER BY ';
            $orderBySql.= $sortby['column'];
            if ($sortby['direction']) $orderBySql.= ' ' . $sortby['direction'];
            while ($sortby= next($this->query['sortby'])) {
                $orderBySql.= ', ';
                $orderBySql.= $sortby['column'];
                if ($sortby['direction']) $orderBySql.= ' ' . $sortby['direction'];
            }
        }
        if ($command == 'SELECT' && !empty($limit) && !empty($offset)) {
        	if (empty($orderBySql)) {
        		$pk = $this->xpdo->getPK($this->getClass());
        		if ($pk) {
        			if (!is_array($pk)) $pk = array($pk);
        			$orderBy = array();
        			foreach ($pk as $k) {
		        		$orderBy[] = $this->xpdo->escape('xpdoLimit1') . '.' . $this->xpdo->escape($this->getAlias() . '_' . $k);
	        		}
	        		$orderBySql = "ORDER BY " . implode(', ', $orderBy);
				}
    		}
    		if (!empty($orderBySql)) {
	        	$sql = "SELECT [xpdoLimit2].* FROM (SELECT [xpdoLimit1].*, ROW_NUMBER() OVER({$orderBySql}) AS [xpdoRowNr] FROM ({$sql}) [xpdoLimit1]) [xpdoLimit2] WHERE [xpdoLimit2].[xpdoRowNr] BETWEEN " . ($offset + 1) . " AND " . ($offset + $limit);
        	} else {
        		$this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "limit() in sqlsrv requires either an explicit sortby or a defined primary key; limit ignored");
        	}
        } else {
        	$sql.= $orderBySql;
        }
        $this->sql= $sql;
        return (!empty ($this->sql));
    }
}
