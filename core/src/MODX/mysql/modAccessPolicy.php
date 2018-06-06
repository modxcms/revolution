<?php

namespace MODX\mysql;


class modAccessPolicy extends \MODX\modAccessPolicy
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'access_policies',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'name' => null,
                'description' => null,
                'parent' => 0,
                'template' => 0,
                'class' => '',
                'data' => '{}',
                'lexicon' => 'permissions',
            ],
        'fieldMeta' =>
            [
                'name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'unique',
                    ],
                'description' =>
                    [
                        'dbtype' => 'mediumtext',
                        'phptype' => 'string',
                    ],
                'parent' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'template' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'class' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'data' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'json',
                        'default' => '{}',
                    ],
                'lexicon' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'permissions',
                    ],
            ],
        'indexes' =>
            [
                'name' =>
                    [
                        'alias' => 'name',
                        'primary' => false,
                        'unique' => true,
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
                'parent' =>
                    [
                        'alias' => 'parent',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'parent' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'class' =>
                    [
                        'alias' => 'class',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'class' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'template' =>
                    [
                        'alias' => 'template',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'template' =>
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
                'Children' =>
                    [
                        'class' => 'MODX\\modAccessPolicy',
                        'local' => 'id',
                        'foreign' => 'parent',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
            ],
        'aggregates' =>
            [
                'Parent' =>
                    [
                        'class' => 'MODX\\modAccessPolicy',
                        'local' => 'parent',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
                'Template' =>
                    [
                        'class' => 'MODX\\modAccessPolicyTemplate',
                        'local' => 'template',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
