<?php

namespace MODX\sqlsrv;


class modFormCustomizationProfileUserGroup extends \MODX\modFormCustomizationProfileUserGroup
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'fc_profiles_usergroups',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'usergroup' => 0,
                'profile' => 0,
            ],
        'fieldMeta' =>
            [
                'usergroup' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'profile' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
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
                                'usergroup' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'profile' =>
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
                'UserGroup' =>
                    [
                        'class' => 'MODX\\modUserGroup',
                        'local' => 'usergroup',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Profile' =>
                    [
                        'class' => 'MODX\\modFormCustomizationProfile',
                        'local' => 'profile',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
