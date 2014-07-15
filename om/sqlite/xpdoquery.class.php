<?php
/*
 * Copyright 2010-2014 by MODX, LLC.
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
 * The SQLite implementation of xPDOQuery.
 *
 * @package xpdo
 * @subpackage om.sqlite
 */

/** Include the base {@see xPDOQuery} class */
include_once (dirname(dirname(__FILE__)) . '/xpdoquery.class.php');

/**
 * An implementation of xPDOQuery for the SQLite database engine.
 *
 * @package xpdo
 * @subpackage om.sqlite
 */
class xPDOQuery_sqlite extends xPDOQuery {
    public function __construct(& $xpdo, $class, $criteria= null) {
        parent :: __construct($xpdo, $class, $criteria);
        $this->query['priority']= '';
    }

    public function construct() {
        $constructed= false;
        $this->bindings= array ();
        $command= strtoupper($this->query['command']);
        $sql= $this->query['command'] . ' ';
        if ($command == 'SELECT') $sql.= $this->query['distinct'] ? $this->query['distinct'] . ' ' : '';
        if ($command == 'SELECT') $sql.= $this->query['priority'] ? $this->query['priority'] . ' ' : '';
        if ($command == 'SELECT') {
            $columns= array ();
            if (empty ($this->query['columns'])) {
                $this->select('*');
            }
            foreach ($this->query['columns'] as $alias => $column) {
                $ignorealias = is_int($alias);
                $escape = !preg_match('/\bAS\b/i', $column) && !preg_match('/\./', $column) && !preg_match('/\(/', $column);
                if ($escape) {
                    $column= $this->xpdo->escape(trim($column));
                } else {
                    $column= trim($column);
                }
                if (!$ignorealias) {
                    $alias = $escape ? $this->xpdo->escape($alias) : $alias;
                    $columns[]= "{$column} AS {$alias}";
                } else {
                    $columns[]= "{$column}";
                }
            }
            $sql.= implode(', ', $columns);
            $sql.= ' ';
        }
        if ($command != 'UPDATE') {
            $sql.= 'FROM ';
        }
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
        if ($command == 'UPDATE') {
            if (!empty($this->query['set'])) {
                reset($this->query['set']);
                $clauses = array();
                while (list($setKey, $setVal) = each($this->query['set'])) {
                    $value = $setVal['value'];
                    $type = $setVal['type'];
                    if ($value !== null && in_array($type, array(PDO::PARAM_INT, PDO::PARAM_STR))) {
                        $value = $this->xpdo->quote($value, $type);
                    } elseif ($value === null) {
                        $value = 'NULL';
                    }
                    $clauses[] = $this->xpdo->escape($setKey) . ' = ' . $value;
                }
                if (!empty($clauses)) {
                    $sql.= 'SET ' . implode(', ', $clauses) . ' ';
                }
                unset($clauses);
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
        if ($command == 'SELECT' && !empty ($this->query['sortby'])) {
            $sortby= reset($this->query['sortby']);
            $sql.= 'ORDER BY ';
            $sql.= $sortby['column'];
            if ($sortby['direction']) $sql.= ' ' . $sortby['direction'];
            while ($sortby= next($this->query['sortby'])) {
                $sql.= ', ';
                $sql.= $sortby['column'];
                if ($sortby['direction']) $sql.= ' ' . $sortby['direction'];
            }
            $sql.= ' ';
        }
        if ($limit= intval($this->query['limit'])) {
            $sql.= 'LIMIT ';
            if ($offset= intval($this->query['offset'])) $sql.= $offset . ', ';
            $sql.= $limit . ' ';
        }
        $this->sql= $sql;
        return (!empty ($this->sql));
    }
}
