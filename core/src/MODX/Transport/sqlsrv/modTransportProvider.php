<?php

namespace MODX\Transport\sqlsrv;

use xPDO\xPDO;

class modTransportProvider extends \MODX\Transport\modTransportProvider
{

    public static $metaMap = [
        'package' => 'MODX\\Transport',
        'version' => '3.0',
        'table' => 'transport_providers',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'name' => null,
                'description' => null,
                'service_url' => null,
                'username' => '',
                'api_key' => '',
                'created' => null,
                'updated' => null,
                'active' => 1,
                'priority' => 10,
                'properties' => '{}',
            ],
        'fieldMeta' =>
            [
                'name' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'unique',
                    ],
                'description' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                    ],
                'service_url' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '512',
                        'phptype' => 'string',
                    ],
                'username' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'api_key' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'created' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'datetime',
                        'null' => false,
                    ],
                'updated' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'timestamp',
                    ],
                'active' =>
                    [
                        'dbtype' => 'bit',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 1,
                        'index' => 'index',
                    ],
                'priority' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '4',
                        'phptype' => 'json',
                        'null' => false,
                        'default' => 10,
                        'index' => 'index',
                    ],
                'properties' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '{}',
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
                'api_key' =>
                    [
                        'alias' => 'api_key',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'api_key' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'username' =>
                    [
                        'alias' => 'username',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'username' =>
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
                'priority' =>
                    [
                        'alias' => 'priority',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'priority' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'aggregates' =>
            [
                'Packages' =>
                    [
                        'class' => 'transport.modTransportPackage',
                        'local' => 'id',
                        'foreign' => 'provider',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
    ];
}
