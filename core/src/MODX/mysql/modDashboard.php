<?php

namespace MODX\mysql;


class modDashboard extends \MODX\modDashboard
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'dashboard',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'name' => '',
                'description' => null,
                'hide_trees' => 0,
                'customizable' => 1,
            ],
        'fieldMeta' =>
            [
                'name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'description' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'string',
                    ],
                'hide_trees' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'customizable' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 1,
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
                'hide_trees' =>
                    [
                        'alias' => 'hide_trees',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'hide_trees' =>
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
                'Placements' =>
                    [
                        'class' => 'MODX\\modDashboardWidgetPlacement',
                        'local' => 'id',
                        'foreign' => 'dashboard',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
        'aggregates' =>
            [
                'UserGroups' =>
                    [
                        'class' => 'MODX\\modUserGroup',
                        'local' => 'id',
                        'foreign' => 'dashboard',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
    ];
}
