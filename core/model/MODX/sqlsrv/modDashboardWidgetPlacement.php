<?php

namespace MODX\sqlsrv;


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
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'dashboard' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'widget' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'rank' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'size' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
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
