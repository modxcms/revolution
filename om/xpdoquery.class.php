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

/**
 * An xPDOCriteria derivative with methods for constructing complex statements.
 *
 * @abstract
 * @package xpdo
 * @subpackage om
 */
abstract class xPDOQuery extends xPDOCriteria {
    const SQL_AND = 'AND';
    const SQL_OR = 'OR';
    const SQL_JOIN_CROSS = 'JOIN';
    const SQL_JOIN_LEFT = 'LEFT JOIN';
    const SQL_JOIN_RIGHT = 'RIGHT JOIN';
    const SQL_JOIN_NATURAL_LEFT = 'NATURAL LEFT JOIN';
    const SQL_JOIN_NATURAL_RIGHT = 'NATURAL RIGHT JOIN';
    const SQL_JOIN_STRAIGHT = 'STRAIGHT_JOIN';

    /**
     * An array of symbols and keywords indicative of SQL operators.
     *
     * @var array
     * @todo Refactor this to separate xPDOQuery operators from db-specific conditional statement identifiers.
     */
    protected $_operators= array (
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
    protected $_quotable= array ('string', 'password', 'date', 'datetime', 'timestamp', 'time');
    protected $_class= null;
    protected $_alias= null;
    public $graph= array ();
    public $query= array (
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

    public function __construct(& $xpdo, $class, $criteria= null) {
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

    public function getClass() {
        return $this->_class;
    }

    public function getAlias() {
        return $this->_alias;
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
    public function command($command= 'SELECT') {
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
    public function setClassAlias($alias= '') {
        $this->_alias= $alias;
        return $this;
    }

    /**
     * Specify columns to return from the SQL query.
     *
     * @param string $columns Columns to return from the query.
     * @return xPDOQuery Returns the current object for convenience.
     */
    abstract public function select($columns= '*');

    /**
     * Join a table represented by the specified class.
     *
     * @param string $class The classname (or relation alias for aggregates and
     * composites) of representing the table to be joined.
     * @param string $alias An optional alias to represent the joined table in
     * the constructed query.
     * @param string $type The type of join to perform.  See the xPDOQuery::SQL_JOIN
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
    abstract public function join($class, $alias= '', $type= xPDOQuery::SQL_JOIN_CROSS, $conditions= array (), $conjunction= xPDOQuery::SQL_AND, $binding= null, $condGroup= 0);

    public function innerJoin($class, $alias= '', $conditions= array (), $conjunction= xPDOQuery::SQL_AND, $binding= null, $condGroup= 0) {
        return $this->join($class, $alias, xPDOQuery::SQL_JOIN_CROSS, $conditions, $conjunction, $binding, $condGroup);
    }

    public function leftJoin($class, $alias= '', $conditions= array (), $conjunction= xPDOQuery::SQL_AND, $binding= null, $condGroup= 0) {
        return $this->join($class, $alias, xPDOQuery::SQL_JOIN_LEFT, $conditions, $conjunction, $binding, $condGroup);
    }

    public function rightJoin($class, $alias= '', $conditions= array (), $conjunction= xPDOQuery::SQL_AND, $binding= null, $condGroup= 0) {
        return $this->join($class, $alias, xPDOQuery::SQL_JOIN_RIGHT, $conditions, $conjunction, $binding, $condGroup);
    }

    /**
     * Add a FROM clause to the query.
     *
     * @param string $class The class representing the table to add.
     * @param string $alias An optional alias for the class.
     * @return xPDOQuery Returns the instance.
     */
    public function from($class, $alias= '') {
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
    public function condition(& $target, $conditions= '1', $conjunction= xPDOQuery::SQL_AND, $binding= null, $condGroup= 0) {
        $condGroup= intval($condGroup);
        if (!isset ($target[$condGroup])) $target[$condGroup]= array ();
        if (!is_array ($conditions)) {
            if (!$this->isConditionalClause($conditions)) {
                $parsed= $this->parseConditions($conditions, $conjunction);
                if (is_array ($parsed) && isset ($parsed['__sql'])) {
                    $target[$condGroup][]= array (
                        'sql' => $parsed['__sql'],
                        'conjunction' => $parsed['__conjunction'],
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
                $this->condition($target, $conditions['__sql'], $conditions['__conjunction'], $conditions['__binding'], $condGroup);
            } else {
                foreach ($this->parseConditions($conditions, $conjunction) as $condition) {
                    if (is_array($condition) && isset ($condition['__sql'])) {
                        $this->condition($target, $condition['__sql'], $condition['__conjunction'], $condition['__binding'], $condGroup);
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
    public function where($conditions= '', $conjunction= xPDOQuery::SQL_AND, $binding= null, $condGroup= 0) {
        $this->condition($this->query['where'], $conditions, $conjunction, $binding, $condGroup);
        return $this;
    }

    public function andCondition($conditions, $binding= null, $group= 0) {
        $this->where($conditions, xPDOQuery::SQL_AND, $binding, $group);
        return $this;
    }
    public function orCondition($conditions, $binding= null, $group= 0) {
        $this->where($conditions, xPDOQuery::SQL_OR, $binding, $group);
        return $this;
    }

    /**
     * Add an ORDER BY clause to the query.
     *
     * @param string $column Column identifier to sort by.
     * @param string $direction The direction to sort by, ASC or DESC.
     * @return xPDOQuery Returns the instance.
     */
    public function sortby($column, $direction= 'ASC') {
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
    public function groupby($column, $direction= '') {
        $this->query['groupby'][]= array ('column' => $column, 'direction' => $direction);
        return $this;
    }

    public function having($conditions) {
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
    public function limit($limit, $offset= 0) {
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
    abstract public function bindGraph($graph);

    /**
     * Bind the node of an object graph to the query.
     *
     * @param string $parentClass The class representing the relation parent.
     * @param string $parentAlias The alias the class is assuming.
     * @param string $classAlias The class representing the related graph node.
     * @param array $relations Child relations of the current graph node.
     */
    abstract public function bindGraphNode($parentClass, $parentAlias, $classAlias, $relations);

    /**
     * Hydrates a graph of related objects from a single result set.
     *
     * @param array $rows A collection of result set rows for hydrating the graph.
     * @return array A collection of objects with all related objects from the graph pre-populated.
     */
    public function hydrateGraph($rows, $cacheFlag = true) {
        $instances= array ();
        $collectionCaching = $this->xpdo->getOption(xPDO::OPT_CACHE_DB_COLLECTIONS, array(), 1);
        if (is_object($rows)) {
            if ($cacheFlag && $this->xpdo->_cacheEnabled && $collectionCaching > 0) {
                $cacheRows = array();
            }
            while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
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

    public function hydrateGraphParent(& $instances, $row) {
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
    public function hydrateGraphNode(& $row, & $instance, $alias, $relations) {
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
    abstract public function construct();

    /**
     * Prepares the xPDOQuery for execution.
     *
     * @return xPDOStatement The xPDOStatement representing the prepared query.
     */
    public function prepare($bindings= array (), $byValue= true, $cacheFlag= null) {
        $this->stmt= null;
        if (empty ($this->sql)) {
            $this->construct();
        }
        if (!empty ($this->sql) && $this->stmt= $this->xpdo->prepare($this->sql)) {
            $this->bind($bindings, $byValue, $cacheFlag);
        }
        return $this->stmt;
    }

    /**
     * Parses an xPDO condition expression.
     *
     * @param mixed $conditions A valid xPDO condition expression.
     * @param string $conjunction The optional conjunction for the condition( s ).
     */
    abstract public function parseConditions($conditions, $conjunction = xPDOQuery::SQL_AND);

    /**
     * Determines if a string contains a conditional operator.
     *
     * @param string $string The string to evaluate.
     * @return boolean True if the string is a complete conditional SQL clause.
     */
    public function isConditionalClause($string) {
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
    public function buildConditionalClause($conditions, $conjunction= xPDOQuery::SQL_AND) {
        $groups= count($conditions);
        $clause= '';
        $currentGroup= 1;
        $isFirst= true;
        foreach ($conditions as $groupKey => $group) {
            $groupClause = '';
            $groupConjunction = $conjunction;
            if ($groups > 1) $groupClause .= ' ( ';
            $groupClause.= $this->buildConditionalGroupClause($group, $isFirst, $groupConjunction);
            if ($groups > 1) $groupClause .= ' ) ';
            if ($currentGroup > 1 && $currentGroup <= $groups) $groupClause = ' ' . $groupConjunction . ' ' . $groupClause;
            $clause .= $groupClause;
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
     * @param string &$conjunction
     * @return string The clause.
     */
    public function buildConditionalGroupClause($condition, $isFirst, & $conjunction) {
        $clause= '';
        reset($condition);
        if (is_int(key($condition))) {
            $origConjunction = $conjunction;
            $isFirst= true;
            foreach ($condition as $subKey => $subGroup) {
                $groupConjunction = $origConjunction;
                $clause.= $this->buildConditionalGroupClause($subGroup, $isFirst, $groupConjunction);
                if ($isFirst) $conjunction = $groupConjunction;
                $isFirst= false;
            }
        } elseif (isset ($condition['sql'])) {
            if (!$isFirst) {
                $clause.= ' ' . $condition['conjunction'] . ' ';
            } else {
                $conjunction = $condition['conjunction'];
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
    public function wrap($criteria) {
        if ($criteria instanceof xPDOQuery) {
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
