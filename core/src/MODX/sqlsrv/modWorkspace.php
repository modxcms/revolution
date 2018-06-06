<?php

namespace MODX\sqlsrv;


class modWorkspace extends \MODX\modWorkspace
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'workspaces',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'name' => '',
                'path' => '',
                'created' => null,
                'active' => 0,
                'attributes' => null,
            ],
        'fieldMeta' =>
            [
                'name' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'path' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'unique',
                    ],
                'created' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'timestamp',
                        'null' => false,
                    ],
                'active' =>
                    [
                        'dbtype' => 'bit',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'attributes' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'array',
                    ],
            ],
        'indexes' =>
            [
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
                'path' =>
                    [
                        'alias' => 'path',
                        'primary' => false,
                        'unique' => true,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'path' =>
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
            ],
        'composites' =>
            [
                'Packages' =>
                    [
                        'class' => 'transport.modTransportPackage',
                        'local' => 'id',
                        'foreign' => 'workspace',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
    ];
}
