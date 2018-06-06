<?php

namespace MODX\mysql;


class modPluginEvent extends \MODX\modPluginEvent
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'site_plugin_events',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'pluginid' => 0,
                'event' => '',
                'priority' => 0,
                'propertyset' => 0,
            ],
        'fieldMeta' =>
            [
                'pluginid' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'event' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'pk',
                    ],
                'priority' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'propertyset' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
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
                                'pluginid' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'event' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'priority' =>
                    [
                        'alias' => 'priority',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'priority' =>
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
                'Plugin' =>
                    [
                        'class' => 'MODX\\modPlugin',
                        'local' => 'pluginid',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Event' =>
                    [
                        'class' => 'MODX\\modEvent',
                        'local' => 'event',
                        'foreign' => 'name',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'PropertySet' =>
                    [
                        'class' => 'MODX\\modPropertySet',
                        'local' => 'propertyset',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
