<?php

namespace MODX\sqlsrv;


class modUserGroupRole extends \MODX\modUserGroupRole
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'user_group_roles',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'name' => null,
                'description' => null,
                'authority' => 9999,
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
                'authority' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 9999,
                        'index' => 'index',
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
                'authority' =>
                    [
                        'alias' => 'authority',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'authority' =>
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
                'UserGroupMembers' =>
                    [
                        'class' => 'MODX\\modUserGroupMember',
                        'local' => 'id',
                        'foreign' => 'role',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
    ];
}
