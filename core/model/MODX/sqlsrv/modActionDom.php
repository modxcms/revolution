<?php

namespace MODX\sqlsrv;


class modActionDom extends \MODX\modActionDom
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'actiondom',
        'extends' => 'MODX\\modAccessibleSimpleObject',
        'fields' =>
            [
                'set' => 0,
                'action' => '',
                'name' => '',
                'description' => null,
                'xtype' => '',
                'container' => '',
                'rule' => '',
                'value' => '',
                'constraint' => '',
                'constraint_field' => '',
                'constraint_class' => '',
                'active' => 1,
                'for_parent' => 0,
                'rank' => 0,
            ],
        'fieldMeta' =>
            [
                'set' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'action' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'name' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'description' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                    ],
                'xtype' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'container' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'rule' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'value' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'constraint' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'constraint_field' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'constraint_class' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'active' =>
                    [
                        'dbtype' => 'bit',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 1,
                        'index' => 'index',
                    ],
                'for_parent' =>
                    [
                        'dbtype' => 'bit',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'rank' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
            ],
        'indexes' =>
            [
                'set' =>
                    [
                        'alias' => 'set',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'set' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'action' =>
                    [
                        'alias' => 'action',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'action' =>
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
                'active' =>
                    [
                        'alias' => 'active',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'active' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'for_parent' =>
                    [
                        'alias' => 'for_parent',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'for_parent' =>
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
                'Access' =>
                    [
                        'class' => 'MODX\\modAccessActionDom',
                        'local' => 'id',
                        'foreign' => 'target',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
        'aggregates' =>
            [
                'FCSet' =>
                    [
                        'class' => 'MODX\\modFormCustomizationSet',
                        'local' => 'set',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Action' =>
                    [
                        'class' => 'MODX\\modAction',
                        'local' => 'action',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
