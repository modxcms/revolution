<?php

namespace MODX\Sources\sqlsrv;

use xPDO\xPDO;

class modMediaSource extends \MODX\Sources\modMediaSource
{

    public static $metaMap = [
        'package' => 'MODX\\Sources',
        'version' => '3.0',
        'table' => 'media_sources',
        'extends' => 'MODX\\modAccessibleObject',
        'fields' =>
            [
                'name' => '',
                'description' => null,
                'class_key' => 'MODX\\Sources\\modFileMediaSource',
                'properties' => null,
                'is_stream' => 1,
            ],
        'fieldMeta' =>
            [
                'name' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'description' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                        'null' => true,
                    ],
                'class_key' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'sources.modFileMediaSource',
                        'index' => 'index',
                    ],
                'properties' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'array',
                        'null' => true,
                    ],
                'is_stream' =>
                    [
                        'dbtype' => 'bit',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 1,
                        'index' => 'index',
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
                'class_key' =>
                    [
                        'alias' => 'class_key',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'class_key' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'is_stream' =>
                    [
                        'alias' => 'is_stream',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'is_stream' =>
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
                'SourceElement' =>
                    [
                        'class' => 'MODX\\Sources\\modMediaSourceElement',
                        'local' => 'id',
                        'foreign' => 'source',
                        'cardinality' => 'one',
                        'owner' => 'local',
                    ],
            ],
        'aggregates' =>
            [
                'Chunks' =>
                    [
                        'class' => 'MODX\\modChunk',
                        'local' => 'id',
                        'foreign' => 'source',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'Plugins' =>
                    [
                        'class' => 'MODX\\modPlugin',
                        'local' => 'id',
                        'foreign' => 'source',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'Snippets' =>
                    [
                        'class' => 'MODX\\modSnippet',
                        'local' => 'id',
                        'foreign' => 'source',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'Templates' =>
                    [
                        'class' => 'MODX\\modTemplate',
                        'local' => 'id',
                        'foreign' => 'source',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'TemplateVars' =>
                    [
                        'class' => 'MODX\\modTemplateVar',
                        'local' => 'id',
                        'foreign' => 'source',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
    ];
}
