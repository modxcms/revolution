<?php
/*
 * Copyright 2006, 2007, 2008, 2009 by  Jason Coward <xpdo@opengeek.com>
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
 * The MySQL implementation of xPDOQuery.
 *
 * @package xpdo
 * @subpackage om.mysql
 */

/** Include the base {@see xPDOQuery} class */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../xpdoquery.class.php');

/**
 * An implementation of xPDOQuery for the MySQL database engine.
 *
 * @package xpdo
 * @subpackage om.mysql
 */
class xPDOQuery_mysql extends xPDOQuery {
    function xPDOQuery_mysql(& $xpdo, $class, $criteria= null) {
        $this->__construct($xpdo, $class, $criteria);
    }
    function __construct(& $xpdo, $class, $criteria= null) {
        parent :: __construct($xpdo, $class, $criteria);
        $this->query['priority']= '';
    }

    function select($columns= '*') {
        if (!is_array($columns)) {
            $columns= trim($columns);
            if ($columns == '*' || $columns === $this->_alias . '.*' || $columns === '`' . $this->_alias . '`.*') {
                $columns= $this->xpdo->getSelectColumns($this->_class, $this->_alias, $this->_alias . '_');
            }
            $columns= explode(',', $columns);
        }
        if (is_array ($columns)) {
            if (!is_array($this->query['columns'])) {
                $this->query['columns']= $columns;
            } else {
                $this->query['columns']= array_merge($this->query['columns'], $columns);
            }
        }
        return $this;
    }

    function join($class, $alias= '', $type= XPDO_SQL_JOIN_CROSS, $conditions= array (), $conjunction= XPDO_SQL_AND, $binding= null, $condGroup= 0) {
        if ($this->xpdo->loadClass($class)) {
            $alias= $alias ? $alias : $class;
            $target= & $this->query['from']['joins'];
            $targetIdx= count($target);
            $target[$targetIdx]= array (
                'table' => $this->xpdo->getTableName($class),
                'class' => $class,
                'alias' => $alias,
                'type' => $type,
                'conditions' => array ()
            );
            if (empty ($conditions)) {
                if ($fkMeta= $this->xpdo->getFKDefinition($this->_class, $alias)) {
                    $parentAlias= isset ($this->_alias) ? $this->_alias : $this->_class;
                    $local= $fkMeta['local'];
                    $foreign= $fkMeta['foreign'];
                    $conditions= "`{$parentAlias}`.`{$local}` = `{$alias}`.`{$foreign}`";
                }
            }
            $this->condition($target[$targetIdx]['conditions'], $conditions, $conjunction, $binding, $condGroup);
        }
        return $this;
    }

    function bindGraph($graph) {
        if (is_string($graph)) {
            $graph= $this->xpdo->fromJSON($graph);
        }
        if (is_array ($graph)) {
            if ($this->graph !== $graph) {
                $this->graph= $graph;
                $this->select($this->xpdo->getSelectColumns($this->_class, $this->_alias, $this->_alias . '_'));
                foreach ($this->graph as $relationAlias => $subRelations) {
                    $this->bindGraphNode($this->_class, $this->_alias, $relationAlias, $subRelations);
                }
                if ($pk= $this->xpdo->getPK($this->_class)) {
                    if (is_array ($pk)) {
                        foreach ($pk as $key) {
                            $this->sortby("`{$this->_alias}`.`{$key}`", 'ASC');
                        }
                    } else {
                        $this->sortby("`{$this->_alias}`.`{$pk}`", 'ASC');
                    }
                }
            }
        }
        return $this;
    }

    function bindGraphNode($parentClass, $parentAlias, $classAlias, $relations) {
        if ($fkMeta= $this->xpdo->getFKDefinition($parentClass, $classAlias)) {
            $class= $fkMeta['class'];
            $local= $fkMeta['local'];
            $foreign= $fkMeta['foreign'];
            $this->select($this->xpdo->getSelectColumns($class, $classAlias, $classAlias . '_'));
            $expression= "`{$parentAlias}`.`{$local}` = `{$classAlias}`.`{$foreign}`";
            $this->leftJoin($class, $classAlias, $expression);
            if (!empty ($relations)) {
                foreach ($relations as $relationAlias => $subRelations) {
                    $this->bindGraphNode($class, $classAlias, $relationAlias, $subRelations);
                }
            }
        }
    }

    function construct() {
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
            $ignorealias= isset ($this->query['columns'][0]);
            foreach ($this->query['columns'] as $alias => $column) {
                $column= trim($column);
                if (!$ignorealias && $alias !== $column) {
                    $columns[]= "{$column} AS `{$alias}`";
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
                $tables[]= $table['table'];
            } else {
                $tables[]= $table['table'] . ' AS `' . $table['alias'] . '`';
            }
        }
        $sql.= $this->query['from']['tables'] ? implode(', ', $tables) . ' ' : '';
        if (!empty ($this->query['from']['joins'])) {
            foreach ($this->query['from']['joins'] as $join) {
                $sql.= $join['type'] . ' ' . $join['table'] . ' `' . $join['alias'] . '` ';
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

    function parseConditions($conditions) {
        $result= array ();
        $pk= $this->xpdo->getPK($this->_class);
        $pktype= $this->xpdo->getPKType($this->_class);
        $fieldMeta= $this->xpdo->getFieldMeta($this->_class);
        $command= strtoupper($this->query['command']);
        $alias= $command == 'SELECT' ? $this->_class : $this->xpdo->getTableName($this->_class, false);
        $alias= trim($alias, $this->xpdo->_escapeChar);
        if (is_array($conditions)) {
            if (isset ($conditions[0]) && !$this->isConditionalClause($conditions[0]) && is_array($pk) && count($conditions) == count($pk)) {
                $iteration= 0;
                $sql= '';
                foreach ($pk as $k) {
                    if (!isset ($conditions[$iteration])) {
                        $conditions[$iteration]= null;
                    }
                    $isString= in_array($fieldMeta[$k]['phptype'], $this->_quotable);
                    $result[$iteration]['__sql']= "{$this->xpdo->_escapeChar}{$alias}{$this->xpdo->_escapeChar}.{$this->xpdo->_escapeChar}{$k}{$this->xpdo->_escapeChar} = ?";
                    $result[$iteration]['__binding']= array (
                        'value' => $conditions[$iteration],
                        'type' => $isString ? PDO_PARAM_STR : PDO_PARAM_INT,
                        'length' => 0
                    );
                    $result[$iteration]['__conjunction']= XPDO_SQL_AND;
                    $iteration++;
                }
            } else {
                $bindings= array ();
                reset($conditions);
                while (list ($key, $val)= each($conditions)) {
                    if (is_int($key)) {
                        if (is_array($val)) {
                            $nested= $this->parseConditions($val);
                            $result = $result + $nested;
                            continue;
                        } elseif ($this->isConditionalClause($val)) {
                            $result[]= $val;
                            continue;
                        } else {
                            $this->xpdo->log(XPDO_LOG_LEVEL_ERROR, "Error parsing condition with key {$key}: " . print_r($val, true));
                        }
                    }
                    $alias= $command == 'SELECT' ? $this->_class : trim($this->xpdo->getTableName($this->_class, false), $this->xpdo->_escapeChar);
                    $operator= '=';
                    $conjunction= XPDO_SQL_AND;
                    $key_operator= explode(':', $key);
                    if ($key_operator && count($key_operator) === 2) {
                        $key= $key_operator[0];
                        $operator= $key_operator[1];
                    }
                    elseif ($key_operator && count($key_operator) === 3) {
                        $conjunction= $key_operator[0];
                        $key= $key_operator[1];
                        $operator= $key_operator[2];
                    }
                    if (strpos($key, '.') !== false) {
                        $key_parts= explode('.', $key);
                        $alias= trim($key_parts[0], " {$this->xpdo->_escapeChar}");
                        $key= $key_parts[1];
                    }
//                    if (array_key_exists($key, $fieldMeta)) {
                        $field= array ();
                        $field['__sql']= "{$this->xpdo->_escapeChar}{$alias}{$this->xpdo->_escapeChar}.{$this->xpdo->_escapeChar}{$key}{$this->xpdo->_escapeChar} " . $operator . ' ?';
                        if ($val === null || strtolower($val) == 'null') {
                            $type= PDO_PARAM_NULL;
                        }
                        elseif (isset($fieldMeta[$key]) && !in_array($fieldMeta[$key]['phptype'], $this->_quotable)) {
                            $type= PDO_PARAM_INT;
                        }
                        else {
                            $type= PDO_PARAM_STR;
                        }
                        $field['__binding']= array (
                            'value' => $val,
                            'type' => $type,
                            'length' => 0
                        );
                        $field['__conjunction']= $conjunction;
                        $result[]= $field;
//                    }
                }
            }
        }
        elseif ($this->isConditionalClause($conditions)) {
            $result= $conditions;
        }
        elseif (($pktype == 'integer' && is_numeric($conditions)) || ($pktype == 'string' && is_string($conditions))) {
            if ($pktype == 'integer') {
                $param_type= PDO_PARAM_INT;
            } else {
                $param_type= PDO_PARAM_STR;
            }
            $result['__sql']= "{$this->xpdo->_escapeChar}{$alias}{$this->xpdo->_escapeChar}.{$this->xpdo->_escapeChar}{$pk}{$this->xpdo->_escapeChar} = ?";
            $result['__binding']= array ('value' => $conditions, 'type' => $param_type, 'length' => 0);
            $result['__conjunction']= XPDO_SQL_AND;
        }
        return $result;
    }
}
