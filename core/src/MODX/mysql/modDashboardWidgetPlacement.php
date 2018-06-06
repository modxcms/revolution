<?php

namespace MODX\mysql;


class modDashboardWidgetPlacement extends \MODX\modDashboardWidgetPlacement
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'dashboard_widget_placement',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'user' => 0,
                'dashboard' => 0,
                'widget' => 0,
                'rank' => 0,
                'size' => 'half',
            ],
        'fieldMeta' =>
            [
                'user' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'dashboard' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'widget' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'rank' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'size' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'half',
                    ],
            ],
        'indexes' =>
            [
                'PRIMARY' =>
                    [
                        'alias' => 'PRIMARY',
                        'primary' => true,
                        'unique' => true,
                        'columns' =>
                            [
                                'user' =>
                                    [
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'dashboard' =>
                                    [
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'widget' =>
                                    [
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'rank' =>
                    [
                        'alias' => 'rank',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'rank' =>
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
                        'local' => 'user',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Dashboard' =>
                    [
                        'class' => 'MODX\\modDashboard',
                        'local' => 'dashboard',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Widget' =>
                    [
                        'class' => 'MODX\\modDashboardWidget',
                        'local' => 'widget',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
