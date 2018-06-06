<?php

namespace MODX\sqlsrv;


class modAccess extends \MODX\modAccess
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'target' => '',
                'principal_class' => 'modPrincipal',
                'principal' => 0,
                'authority' => 9999,
                'policy' => 0,
            ],
        'fieldMeta' =>
            [
                'target' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'fk',
                    ],
                'principal_class' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'modPrincipal',
                        'index' => 'index',
                    ],
                'principal' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'fk',
                    ],
                'authority' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 9999,
                        'index' => 'index',
                    ],
                'policy' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'fk',
                    ],
            ],
        'indexes' =>
            [
                'target' =>
                    [
                        'alias' => 'target',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'target' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'principal_class' =>
                    [
                        'alias' => 'principal_class',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'principal_class' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'principal' =>
                    [
                        'alias' => 'principal',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'principal' =>
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
                'policy' =>
                    [
                        'alias' => 'policy',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'policy' =>
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
                'Policy' =>
                    [
                        'class' => 'MODX\\modAccessPolicy',
                        'local' => 'policy',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
                'Principal' =>
                    [
                        'class' => 'MODX\\modPrincipal',
                        'local' => 'principal',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
                'GroupPrincipal' =>
                    [
                        'class' => 'MODX\\modUserGroup',
                        'local' => 'principal',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                        'criteria' =>
                            [
                                'local' =>
                                    [
                                        'principal_class' => 'modUserGroup',
                                    ],
                            ],
                    ],
                'UserPrincipal' =>
                    [
                        'class' => 'MODX\\modUserGroup',
                        'local' => 'principal',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                        'criteria' =>
                            [
                                'local' =>
                                    [
                                        'principal_class' => 'modUser',
                                    ],
                            ],
                    ],
                'MinimumRole' =>
                    [
                        'class' => 'MODX\\modUserGroupRole',
                        'local' => 'authority',
                        'foreign' => 'authority',
                        'owner' => 'local',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
