<?php

namespace MODX\sqlsrv;


class modManagerLog extends \MODX\modManagerLog
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'manager_log',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'user' => 0,
                'occurred' => '0000-00-00 00:00:00',
                'action' => '',
                'classKey' => '',
                'item' => '0',
            ],
        'fieldMeta' =>
            [
                'user' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'occurred' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'datetime',
                        'null' => true,
                        'default' => '0000-00-00 00:00:00',
                    ],
                'action' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'classKey' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'item' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '0',
                    ],
            ],
        'aggregates' =>
            [
                'User' =>
                    [
                        'class' => 'MODX\\modUser',
                        'local' => 'user',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
