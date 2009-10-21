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
 * A class for constructing complex SQL statements using a model-aware API.
 *
 * @package xpdo
 * @subpackage om
 */
if (!defined('XPDO_SQL_AND')) {
    /**#@+
     * @var string
     * @access public
     */
    define('XPDO_SQL_AND', 'AND');
    define('XPDO_SQL_OR', 'OR');
    define('XPDO_SQL_JOIN_CROSS', 'JOIN');
    define('XPDO_SQL_JOIN_LEFT', 'LEFT JOIN');
    define('XPDO_SQL_JOIN_RIGHT', 'RIGHT JOIN');
    define('XPDO_SQL_JOIN_NATURAL_LEFT', 'NATURAL LEFT JOIN');
    define('XPDO_SQL_JOIN_NATURAL_RIGHT', 'NATURAL RIGHT JOIN');
    define('XPDO_SQL_JOIN_STRAIGHT', 'STRAIGHT_JOIN');
    /**#@-*/
}

/**
 * An xPDOCriteria derivative with methods for constructing complex statements.
 *
 * @abstract
 * @package xpdo
 * @subpackage om
 */
class xPDOQuery extends xPDOCriteria {
    /**
     * An array of symbols and keywords indicative of SQL operators.
     *
     * @var array
     * @todo Refactor this to separate xPDOQuery operators from db-specific conditional statement identifiers.
     */
    var $_operators= array (
        '=',
        '!=',
        '<',
        '<=',
        '>',
        '>=',
        '<=>',
        ' LIKE ',
        ' IS NULL',
        ' IS NOT NULL',
        ' BETWEEN ',
        ' IN ',
        ' IN(',
        ' NOT(',
        ' NOT (',
        ' NOT IN ',
        ' NOT IN(',
        ' EXISTS (',
        ' EXISTS(',
        ' NOT EXISTS (',
        ' NOT EXISTS(',
        ' COALESCE(',
        ' GREATEST(',
        ' INTERVAL(',
        ' LEAST(',
    );
    var $_quotable= array ('string', 'password', 'date', 'datetime', 'timestamp', 'time');
    var $_class= null;
    var $_alias= null;
    var $graph= array ();
    var $query= array (
        'command' => 'SELECT',
        'distinct' => '',
        'columns' => '',
        'from' => array (
            'tables' => array (),
            'joins' => array (),
        ),
        'where' => array (),
        'groupby' => array (),
        'having' => array (),
        'orderby' => array (),
        'offset' => '',
        'limit' => '',
    );

    function xPDOQuery(& $xpdo, $class, $criteria= null) {
        $this->__construct($xpdo, $class, $criteria);
    }
    function __construct(& $xpdo, $class, $criteria= null) {
        parent :: __construct($xpdo);
        if ($class= $this->xpdo->loadClass($class)) {
            $this->_class= $class;
            $this->_alias= $class;
            $this->query['from']['tables'][0]= array (
                'table' => $this->xpdo->getTableName($this->_class),
                'alias' => & $this->_alias
            );
            if ($criteria !== null) {
                if (is_object($criteria)) {
                    $this->wrap($criteria);
                }
                else {
                    $this->where($criteria);
                }
            }
        }
    }

    /**
     * Set the type of SQL command you want to build.
     *
     * The default is SELECT, though it also supports DELETE.
     *
     * @todo Implement support for other standard SQL statements such as UPDATE.
     * @param string $command The type of SQL statement represented by this
     * object.  Default is 'SELECT'.
     * @return xPDOQuery Returns the current object for convenience.
     */
    function command($command= 'SELECT') {
        $command= strtoupper(trim($command));
        if (preg_match('/(SELECT|DELETE)/', $command)) {
            $this->query['command']= $command;
            if ($command == 'DELETE') $this->_alias= $this->xpdo->getTableName($this->_class);
        }
        return $this;
    }

    /**
     * Sets a SQL alias for the table represented by the main class.
     *
     * @param string $alias An alias for the main table for the SQL statement.
     * @return xPDOQuery Returns the current object for convenience.
     */
    function setClassAlias($alias= '') {
        $this->_alias= $alias;
        return $this;
    }

    /**
     * Specify columns to return from the SQL query.
     *
     * @param string $columns Columns to return from the query.
     * @return xPDOQuery Returns the current object for convenience.
     */
    function select($columns= '*') {
        $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, 'xPDOQuery must be loaded for a specific database platform.');
        return $this;
    }

    /**
     * Join a table represented by the specified class.
     *
     * @param string $class The classname (or relation alias for aggregates and
     * composites) of representing the table to be joined.
     * @param string $alias An optional alias to represent the joined table in
     * the constructed query.
     * @param string $type The type of join to perform.  See the XPDO_SQL_JOIN
     * constants.
     * @param mixed $conditions Conditions of the join specified in any xPDO
     * compatible criteria object or expression.
     * @param string $conjunction A conjunction to be applied to the condition
     * or conditions supplied.
     * @param array $binding Optional bindings to accompany the conditions.
     * @param int $condGroup An optional identifier for adding the conditions
     * to a specific set of conjoined expressions.
     * @return xPDOQuery Returns the current object for convenience.
     */
    function join($class, $alias= '', $type= XPDO_SQL_JOIN_CROSS, $conditions= array (), $conjunction= XPDO_SQL_AND, $binding= null, $condGroup= 0) {
        $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, 'xPDOQuery must be loaded for a specific database platform.');
        return $this;
    }

    function innerJoin($class, $alias= '', $conditions= array (), $conjunction= XPDO_SQL_AND, $binding= null, $condGroup= 0) {
        return $this->join($class, $alias, XPDO_SQL_JOIN_CROSS, $conditions, $conjunction, $binding, $condGroup);
    }

    function leftJoin($class, $alias= '', $conditions= array (), $conjunction= XPDO_SQL_AND, $binding= null, $condGroup= 0) {
        return $this->join($class, $alias, XPDO_SQL_JOIN_LEFT, $conditions, $conjunction, $binding, $condGroup);
    }

    function rightJoin($class, $alias= '', $conditions= array (), $conjunction= XPDO_SQL_AND, $binding= null, $condGroup= 0) {
        return $this->join($class, $alias, XPDO_SQL_JOIN_RIGHT, $conditions, $conjunction, $binding, $condGroup);
    }

    /**
     * Add a FROM clause to the query.
     *
     * @param string $class The class representing the table to add.
     * @param string $alias An optional alias for the class.
     * @return xPDOQuery Returns the instance.
     */
    function from($class, $alias= '') {
        if ($class= $this->xpdo->loadClass($class)) {
            $alias= $alias ? $alias : $class;
            $this->query['from']['tables'][]= array (
                'table' => $this->xpdo->getTableName($class),
                'alias' => $alias
            );
        }
        return $this;
    }

    /**
     * Add a condition to the query.
     *
     * @param string $target The target clause for the condition.
     * @param mixed $conditions A valid xPDO criteria expression.
     * @param string $conjunction The conjunction to use when appending this condition, i.e., AND or OR.
     * @param mixed $binding A value or PDO binding representation of a value for the condition.
     * @param integer $condGroup A numeric identifier for associating conditions into groups.
     * @return xPDOQuery Returns the instance.
     */
    function condition(& $target, $conditions= '1', $conjunction= XPDO_SQL_AND, $binding= null, $condGroup= 0) {
        $condGroup= intval($condGroup);
        if (!isset ($target[$condGroup])) $target[$condGroup]= array ();
        if (!is_array ($conditions)) {
            if (!$this->isConditionalClause($conditions)) {
                $parsed= $this->parseConditions($conditions);
                if (is_array ($parsed) && isset ($parsed['__sql'])) {
                    $target[$condGroup][]= array (
                        'sql' => $parsed['__sql'],
                        'conjunction' => $conjunction,
                        'binding' => $parsed['__binding']
                    );
                }
            } else {
                $target[$condGroup][]= array (
                    'sql' => $conditions,
                    'conjunction' => $conjunction,
                    'binding' => $binding
                );
            }
        }
        else {
            if (isset ($conditions['__sql'])) {
                $this->condition($target, $conditions['__sql'], $conjunction, $conditions['__binding'], $condGroup);
            } else {
                foreach ($this->parseConditions($conditions) as $condition) {
                    if (is_array($condition) && isset ($condition['__sql'])) {
                        $this->condition($target, $condition['__sql'], $conjunction, $condition['__binding'], $condGroup);
                    } else {
                        $this->condition($target, $condition, $conjunction, $binding, $condGroup);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Add a WHERE condition to the query.
     *
     * @param mixed $conditions A valid xPDO criteria expression.
     * @param string $conjunction The conjunction to use when appending this condition, i.e., AND or OR.
     * @param mixed $binding A value or PDO binding representation of a value for the condition.
     * @param integer $condGroup A numeric identifier for associating conditions into groups.
     * @return xPDOQuery Returns the instance.
     */
    function where($conditions= '', $conjunction= XPDO_SQL_AND, $binding= null, $condGroup= 0) {
        $this->condition($this->query['where'], $conditions, $conjunction, $binding, $condGroup);
        return $this;
    }

    function andCondition($conditions, $binding= null, $group= 0) {
        $this->where($conditions, XPDO_SQL_AND, $binding, $group);
        return $this;
    }
    function orCondition($conditions, $binding= null, $group= 0) {
        $this->where($conditions, XPDO_SQL_OR, $binding, $group);
        return $this;
    }

    /**
     * Add an ORDER BY clause to the query.
     *
     * @param string $column Column identifier to sort by.
     * @param string $direction The direction to sort by, ASC or DESC.
     * @return xPDOQuery Returns the instance.
     */
    function sortby($column, $direction= 'ASC') {
        $this->query['sortby'][]= array ('column' => $column, 'direction' => $direction);
        return $this;
    }

    /**
     * Add an GROUP BY clause to the query.
     *
     * @param string $column Column identifier to group by.
     * @param string $direction The direction to sort by, ASC or DESC.
     * @return xPDOQuery Returns the instance.
     */
    function groupby($column, $direction= '') {
        $this->query['groupby'][]= array ('column' => $column, 'direction' => $direction);
        return $this;
    }

    function having($conditions) {
        //TODO: implement HAVING clause support
        return $this;
    }

    /**
     * Add a LIMIT/OFFSET clause to the query.
     *
     * @param integer $limit The number of records to return.
     * @param integer $offset The location in the result set to start from.
     * @return xPDOQuery Returns the instance.
     */
    function limit($limit, $offset= 0) {
        $this->query['limit']= $limit;
        $this->query['offset']= $offset;
        return $this;
    }

    /**
     * Bind an object graph to the query.
     *
     * @param mixed $graph An array or JSON graph of related objects.
     * @return xPDOQuery Returns the instance.
     */
    function bindGraph($graph) {
        $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, 'xPDOQuery must be loaded for a specific database platform.');
        return $this;
    }

    /**
     * Bind the node of an object graph to the query.
     *
     * @param string $parentClass The class representing the relation parent.
     * @param string $parentAlias The alias the class is assuming.
     * @param string $classAlias The class representing the related graph node.
     * @param array $relations Child relations of the current graph node.
     */
    function bindGraphNode($parentClass, $parentAlias, $classAlias, $relations) {
        $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, 'xPDOQuery must be loaded for a specific database platform.');
    }

    /**
     * Hydrates a graph of related objects from a single result set.
     *
     * @param array $rows A collection of result set rows for hydrating the graph.
     * @return array A collection of objects with all related objects from the graph pre-populated.
     */
    function hydrateGraph($rows, $cacheFlag = true) {
        $instances= array ();
        $collectionCaching = $this->xpdo->getOption(XPDO_OPT_CACHE_DB_COLLECTIONS, array(), 1);
        if (is_object($rows)) {
            if ($cacheFlag && $this->xpdo->_cacheEnabled && $collectionCaching > 0) {
                $cacheRows = array();
            }
            while ($row = $rows->fetch(PDO_FETCH_ASSOC)) {
                $this->hydrateGraphParent($instances, $row);
                if ($cacheFlag && $this->xpdo->_cacheEnabled && $collectionCaching > 0) {
                    $cacheRows[]= $row;
                }
            }
            if ($cacheFlag && $this->xpdo->_cacheEnabled && $collectionCaching > 0) {
                $this->xpdo->toCache($this, $cacheRows, $cacheFlag);
            }
        } elseif (is_array($rows)) {
            foreach ($rows as $row) {
                $this->hydrateGraphParent($instances, $row);
            }
        }
        return $instances;
    }

    function hydrateGraphParent(& $instances, $row) {
        $hydrated = false;
        if ($instance= $this->xpdo->newObject($this->_class)) {
            $instance->_lazy= array_keys($instance->_fields);
            $instance->fromArray($row, $this->_alias . '_', true, true);
            $pk= $instance->getPrimaryKey();
            if (is_array($pk)) $pk= implode('-', $pk);
            if (isset ($instances[$pk])) {
                $instance= & $instances[$pk];
            } else {
                $instance->_dirty= array ();
                $instance->_new= false;
            }
            foreach ($this->graph as $relationAlias => $subRelations) {
                $this->hydrateGraphNode($row, $instance, $relationAlias, $subRelations);
            }
            $instances[$pk]= $instance;
            $hydrated = true;
        }
        return $hydrated;
    }

    /**
     * Hydrates a node of the object graph.
     *
     * @param array $row The result set representing the current node.
     * @param xPDOObject $instance The xPDOObject instance to be hydrated from the node.
     * @param string $alias The alias identifying the object in the parent relationship.
     * @param array $relations Child relations of the current node.
     */
    function hydrateGraphNode(& $row, & $instance, $alias, $relations) {
        $relObj= null;
        if ($relationMeta= $instance->getFKDefinition($alias)) {
            if ($row[$alias.'_'.$relationMeta['foreign']] != null) {
                if ($relObj= $this->xpdo->newObject($relationMeta['class'])) {
                    $relObj->_lazy= array_keys($relObj->_fields);
                    $prefix= $alias . '_';
                    $relObj->fromArray($row, $prefix, true, true);
                    $relObj->_new= false;
                    $relObj->_dirty= array ();
                    if (strtolower($relationMeta['cardinality']) == 'many') {
                        $instance->addMany($relObj, $alias);
                    }
                    else {
                        $instance->addOne($relObj, $alias);
                    }
                }
            }
        }
        if (!empty ($relations) && is_object($relObj)) {
            while (list($relationAlias, $subRelations)= each($relations)) {
                if (is_array($subRelations) && !empty($subRelations)) {
                    foreach ($subRelations as $subRelation) {
                        $this->hydrateGraphNode($row, $relObj, $relationAlias, $subRelation);
                    }
                } else {
                    $this->hydrateGraphNode($row, $relObj, $relationAlias, null);
                }
            }
        }
    }

    /**
     * Constructs the SQL query from the xPDOQuery definition.
     *
     * @return boolean Returns true if a SQL statement was successfully constructed.
     */
    function construct() {
        $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, 'xPDOQuery must be loaded for a specific database platform.');
        return (!empty ($this->sql));
    }

    /**
     * Prepares the xPDOQuery for execution.
     *
     * @return xPDOStatement The xPDOStatement representing the prepared query.
     */
    function prepare() {
        $this->stmt= null;
        if (empty ($this->sql)) {
            $this->construct();
        }
        if (!empty ($this->sql) && $this->stmt= $this->xpdo->prepare($this->sql)) {
            $this->bind();
        }
        return $this->stmt;
    }

    /**
     * Parses an xPDO condition expression.
     *
     * @param mixed $conditions A valid xPDO condition expression.
     */
    function parseConditions($conditions) {
        $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, 'xPDOQuery must be loaded for a specific database platform.');
    }

    /**
     * Determines if a string contains a conditional operator.
     *
     * @param string $string The string to evaluate.
     * @return boolean True if the string is a complete conditional SQL clause.
     */
    function isConditionalClause($string) {
        $matched= false;
        foreach ($this->_operators as $operator) {
            if (strpos(strtoupper($string), $operator) !== false) {
                $matched= true;
                break;
            }
        }
        return $matched;
    }

    /**
     * Builds conditional clauses from xPDO condition expressions.
     *
     * @param array $conditions
     * @param string $conjunction
     * @return string The conditional clause.
     */
    function buildConditionalClause($conditions, $conjunction= XPDO_SQL_AND) {
        $groups= count($conditions);
        $clause= '';
        $currentGroup= 1;
        $isFirst= true;
        foreach ($conditions as $groupKey => $group) {
            if ($groups > 1) $clause .= ' ( ';
            $clause.= $this->buildConditionalGroupClause($group, $isFirst);
            if ($groups > 1) $clause .= ' ) ';
            if ($currentGroup < $groups) $clause .= ' ' . $conjunction . ' ';
            $currentGroup++;
            $isFirst= false;
        }
        return $clause;
    }

    /**
     * Builds a group of conditional clauses.
     *
     * @param array $condition
     * @param boolean $isFirst
     * @return string The clause.
     */
    function buildConditionalGroupClause($condition, $isFirst) {
        $clause= '';
        reset($condition);
        if (is_int(key($condition))) {
            $isFirst= true;
            foreach ($condition as $subKey => $subGroup) {
                $clause.= $this->buildConditionalGroupClause($subGroup, $isFirst);
                $isFirst= false;
            }
        } elseif (isset ($condition['sql'])) {
            if (!$isFirst) {
                $clause.= ' ' . $condition['conjunction'] . ' ';
            }
            $clause.= $condition['sql'];
            if (isset ($condition['binding']) && !empty ($condition['binding'])) {
                $this->bindings[]= $condition['binding'];
            }
        }
        return $clause;
    }

    /**
     * Wrap an existing xPDOCriteria into this xPDOQuery instance.
     *
     * @param xPDOCriteria $criteria
     */
    function wrap($criteria) {
        if (is_a($criteria, 'xPDOQuery')) {
            $this->_class= $criteria->_class;
            $this->_alias= $criteria->_alias;
            $this->graph= $criteria->graph;
            $this->query= $criteria->query;
        }
        $this->sql= $criteria->sql;
        $this->stmt= $criteria->stmt;
        $this->bindings= $criteria->bindings;
        $this->cacheFlag= $criteria->cacheFlag;
    }
}
