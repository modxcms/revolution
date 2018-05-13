<?php

namespace MODX\mysql;


class modContentType extends \MODX\modContentType
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'content_type',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'name' => null,
                'description' => null,
                'mime_type' => null,
                'file_extensions' => null,
                'headers' => null,
                'binary' => 0,
            ],
        'fieldMeta' =>
            [
                'name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'unique',
                    ],
                'description' =>
                    [
                        'dbtype' => 'tinytext',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'mime_type' =>
                    [
                        'dbtype' => 'tinytext',
                        'phptype' => 'string',
                    ],
                'file_extensions' =>
                    [
                        'dbtype' => 'tinytext',
                        'phptype' => 'string',
                    ],
                'headers' =>
                    [
                        'dbtype' => 'mediumtext',
                        'phptype' => 'array',
                    ],
                'binary' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                    ],
            ],
        'indexes' =>
            [
                'name' =>
                    [
                        'alias' => 'name',
                        'primary' => false,
                        'unique' => true,
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
            ],
        'aggregates' =>
            [
                'Resources' =>
                    [
                        'class' => 'MODX\\modResource',
                        'local' => 'id',
                        'foreign' => 'content_type',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
            ],
        'validation' =>
            [
                'rules' =>
                    [
                        'name' =>
                            [
                                'name' =>
                                    [
                                        'type' => 'xPDOValidationRule',
                                        'rule' => 'xPDOMinLengthValidationRule',
                                        'value' => '1',
                                        'message' => 'content_type_err_ns_name',
                                    ],
                            ],
                    ],
            ],
    ];
}
