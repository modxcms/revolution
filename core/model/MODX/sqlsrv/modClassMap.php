<?php

namespace MODX\sqlsrv;


class modClassMap extends \MODX\modClassMap
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'class_map',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'class' => '',
                'parent_class' => '',
                'name_field' => 'name',
                'path' => '',
                'lexicon' => 'core:resource',
            ],
        'fieldMeta' =>
            [
                'class' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '120',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'unique',
                    ],
                'parent_class' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '120',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'name_field' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'name',
                        'index' => 'index',
                    ],
                'path' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '512',
                        'phptype' => 'string',
                        'default' => '',
                    ],
                'lexicon' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'core:resource',
                    ],
            ],
        'indexes' =>
            [
                'class' =>
                    [
                        'alias' => 'class',
                        'primary' => false,
                        'unique' => true,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'class' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'parent_class' =>
                    [
                        'alias' => 'parent_class',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'parent_class' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'name_field' =>
                    [
                        'alias' => 'name_field',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'name_field' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
    ];
}
