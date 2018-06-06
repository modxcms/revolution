<?php

namespace MODX\mysql;


class modPlugin extends \MODX\modPlugin
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'site_plugins',
        'extends' => 'MODX\\modScript',
        'fields' =>
            [
                'cache_type' => 0,
                'plugincode' => '',
                'locked' => 0,
                'properties' => null,
                'disabled' => 0,
                'moduleguid' => '',
                'static' => 0,
                'static_file' => '',
            ],
        'fieldMeta' =>
            [
                'cache_type' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'plugincode' =>
                    [
                        'dbtype' => 'mediumtext',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'locked' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'properties' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'array',
                        'null' => true,
                    ],
                'disabled' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'moduleguid' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '32',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'fk',
                    ],
                'static' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'static_file' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
            ],
        'fieldAliases' =>
            [
                'content' => 'plugincode',
            ],
        'indexes' =>
            [
                'locked' =>
                    [
                        'alias' => 'locked',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'locked' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'disabled' =>
                    [
                        'alias' => 'disabled',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'disabled' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'static' =>
                    [
                        'alias' => 'static',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'static' =>
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
                'PropertySets' =>
                    [
                        'class' => 'MODX\\modElementPropertySet',
                        'local' => 'id',
                        'foreign' => 'element',
                        'owner' => 'local',
                        'cardinality' => 'many',
                        'criteria' =>
                            [
                                'foreign' =>
                                    [
                                        'element_class' => 'modPlugin',
                                    ],
                            ],
                    ],
                'PluginEvents' =>
                    [
                        'class' => 'MODX\\modPluginEvent',
                        'local' => 'id',
                        'foreign' => 'pluginid',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
        'validation' =>
            [
                'rules' =>
                    [
                        'name' =>
                            [
                                'invalid' =>
                                    [
                                        'type' => 'preg_match',
                                        'rule' => '/^(?!\\s)[a-zA-Z0-9_-\\x7f-\\xff\\s]+(?!\\s)$/',
                                        'message' => 'plugin_err_invalid_name',
                                    ],
                            ],
                    ],
            ],
    ];
}
