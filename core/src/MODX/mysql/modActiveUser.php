<?php

namespace MODX\mysql;


class modActiveUser extends \MODX\modActiveUser
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'active_users',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'internalKey' => 0,
                'username' => '',
                'lasthit' => 0,
                'id' => null,
                'action' => '',
                'ip' => '',
            ],
        'fieldMeta' =>
            [
                'internalKey' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '9',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'username' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '50',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'lasthit' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '20',
                        'phptype' => 'timestamp',
                        'null' => false,
                        'default' => 0,
                    ],
                'id' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => true,
                    ],
                'action' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'ip' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '20',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
            ],
        'indexes' =>
            [
                'internalKey' =>
                    [
                        'alias' => 'internalKey',
                        'primary' => true,
                        'unique' => true,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'internalKey' =>
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
                'User' =>
                    [
                        'class' => 'MODX\\modUser',
                        'local' => 'internalKey',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
