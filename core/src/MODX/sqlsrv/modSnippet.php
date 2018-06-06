<?php

namespace MODX\sqlsrv;


class modSnippet extends \MODX\modSnippet
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'site_snippets',
        'extends' => 'MODX\\modScript',
        'fields' =>
            [
                'cache_type' => 0,
                'snippet' => null,
                'locked' => 0,
                'properties' => null,
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
                'snippet' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                    ],
                'locked' =>
                    [
                        'dbtype' => 'bit',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'properties' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'array',
                        'null' => true,
                    ],
                'moduleguid' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '32',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'fk',
                    ],
                'static' =>
                    [
                        'dbtype' => 'bit',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'static_file' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
            ],
        'fieldAliases' =>
            [
                'content' => 'snippet',
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
                'moduleguid' =>
                    [
                        'alias' => 'moduleguid',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'moduleguid' =>
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
                                        'element_class' => 'modSnippet',
                                    ],
                            ],
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
                                        'rule' => '/^(?!\\s)[a-zA-Z0-9\\x2d-\\x2f\\x7f-\\xff-_\\s]+(?!\\s)$/',
                                        'message' => 'snippet_err_invalid_name',
                                    ],
                            ],
                    ],
            ],
    ];
}
