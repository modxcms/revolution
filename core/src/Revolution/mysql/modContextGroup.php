<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */
namespace MODX\Revolution\mysql;

class modContextGroup extends \MODX\Revolution\modContextGroup
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'context_groups',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' =>
        array (
            'engine' => 'InnoDB',
        ),
        'fields' =>
        array (
            'name' => '',
            'description' => null,
            'rank' => 0,
        ),
        'fieldMeta' =>
        array (
            'name' =>
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'unique',
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
            'name' =>
            array (
                'alias' => 'name',
                'primary' => false,
                'unique' => true,
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
            'Acls' =>
            array (
                'class' => 'MODX\\Revolution\\modAccessContextGroup',
                'local' => 'id',
                'foreign' => 'target',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
        ),
        'aggregates' =>
        array (
            'Contexts' =>
            array (
                'class' => 'MODX\\Revolution\\modContext',
                'local' => 'id',
                'foreign' => 'context_group',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
        'validation' =>
        array (
            'rules' =>
            array (
                'name' =>
                array (
                    'preventBlank' =>
                    array (
                        'type' => 'xPDOValidationRule',
                        'rule' => 'xPDO\\Validation\\xPDOMinLengthValidationRule',
                        'value' => '1',
                        'message' => 'context_group_err_ns_name',
                    ),
                ),
            ),
        ),
    );

}
