<?php

namespace MODX\sqlsrv;


class modUser extends \MODX\modUser
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'users',
        'extends' => 'MODX\\modPrincipal',
        'fields' =>
            [
                'username' => '',
                'password' => '',
                'cachepwd' => '',
                'class_key' => 'modUser',
                'active' => 1,
                'remote_key' => null,
                'remote_data' => null,
                'hash_class' => 'MODX\\Hashing\\modPBKDF2',
                'salt' => '',
                'primary_group' => 0,
                'session_stale' => null,
                'sudo' => 0,
                'createdon' => 0,
            ],
        'fieldMeta' =>
            [
                'username' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'unique',
                    ],
                'password' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'cachepwd' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'class_key' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'modUser',
                        'index' => 'index',
                    ],
                'active' =>
                    [
                        'dbtype' => 'bit',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 1,
                    ],
                'remote_key' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => true,
                        'index' => 'index',
                    ],
                'remote_data' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'json',
                        'null' => true,
                    ],
                'hash_class' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'MODX\\Hashing\\modPBKDF2',
                    ],
                'salt' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'primary_group' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'session_stale' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'array',
                        'null' => true,
                    ],
                'sudo' =>
                    [
                        'dbtype' => 'bit',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                    ],
                'createdon' =>
                    [
                        'dbtype' => 'bigint',
                        'phptype' => 'timestamp',
                        'null' => false,
                        'default' => 0,
                    ],
            ],
        'indexes' =>
            [
                'username' =>
                    [
                        'alias' => 'username',
                        'primary' => false,
                        'unique' => true,
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
                'class_key' =>
                    [
                        'alias' => 'class_key',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'class_key' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'remote_key' =>
                    [
                        'alias' => 'remote_key',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'remote_key' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'primary_group' =>
                    [
                        'alias' => 'primary_group',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'primary_group' =>
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
                'Profile' =>
                    [
                        'class' => 'MODX\\modUserProfile',
                        'local' => 'id',
                        'foreign' => 'internalKey',
                        'cardinality' => 'one',
                        'owner' => 'local',
                    ],
                'UserSettings' =>
                    [
                        'class' => 'MODX\\modUserSetting',
                        'local' => 'id',
                        'foreign' => 'user',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'UserGroupMembers' =>
                    [
                        'class' => 'MODX\\modUserGroupMember',
                        'local' => 'id',
                        'foreign' => 'member',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'DashboardWidgets' =>
                    [
                        'class' => 'MODX\\modDashboardWidgetPlacement',
                        'local' => 'id',
                        'foreign' => 'user',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
        'aggregates' =>
            [
                'CreatedResources' =>
                    [
                        'class' => 'MODX\\modResource',
                        'local' => 'id',
                        'foreign' => 'createdby',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'EditedResources' =>
                    [
                        'class' => 'MODX\\modResource',
                        'local' => 'id',
                        'foreign' => 'editedby',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'DeletedResources' =>
                    [
                        'class' => 'MODX\\modResource',
                        'local' => 'id',
                        'foreign' => 'deletedby',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'PublishedResources' =>
                    [
                        'class' => 'MODX\\modResource',
                        'local' => 'id',
                        'foreign' => 'publishedby',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'SentMessages' =>
                    [
                        'class' => 'MODX\\modUserMessage',
                        'local' => 'id',
                        'foreign' => 'sender',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'ReceivedMessages' =>
                    [
                        'class' => 'MODX\\modUserMessage',
                        'local' => 'id',
                        'foreign' => 'recipient',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'PrimaryGroup' =>
                    [
                        'class' => 'MODX\\modUserGroup',
                        'local' => 'primary_group',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
