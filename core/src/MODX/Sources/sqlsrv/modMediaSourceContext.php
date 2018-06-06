<?php

namespace MODX\Sources\sqlsrv;

use xPDO\xPDO;

class modMediaSourceContext extends \MODX\Sources\modMediaSourceContext
{

    public static $metaMap = [
        'package' => 'MODX\\Sources',
        'version' => '3.0',
        'table' => 'media_sources_contexts',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'source' => 0,
                'context_key' => 'web',
            ],
        'fieldMeta' =>
            [
                'source' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'context_key' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'web',
                        'index' => 'pk',
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
                                'source' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'context_key' =>
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
                'Source' =>
                    [
                        'class' => 'MODX\\Sources\\modMediaSource',
                        'local' => 'source',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Context' =>
                    [
                        'class' => 'MODX\\modContext',
                        'local' => 'context_key',
                        'foreign' => 'key',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
