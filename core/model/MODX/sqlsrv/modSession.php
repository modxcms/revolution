<?php

namespace MODX\sqlsrv;


class modSession extends \MODX\modSession
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'session',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'id' => '',
                'access' => null,
                'data' => null,
            ],
        'fieldMeta' =>
            [
                'id' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'pk',
                        'default' => '',
                    ],
                'access' =>
                    [
                        'dbtype' => 'bigint',
                        'phptype' => 'timestamp',
                        'null' => false,
                    ],
                'data' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                    ],
            ],
        'indexes' =>
            [
                'PRIMARY' =>
                    [
                        'alias' => 'PRIMARY',
                        'primary' => true,
                        'unique' => true,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'id' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'access' =>
                    [
                        'alias' => 'access',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'access' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'validation' =>
            [
                'rules' =>
                    [
                        'id' =>
                            [
                                'invalid' =>
                                    [
                                        'type' => 'preg_match',
                                        'rule' => '/^[0-9a-zA-Z,-]{22,255}$/',
                                        'message' => 'session_err_invalid_id',
                                    ],
                            ],
                    ],
            ],
    ];
}
