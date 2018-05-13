<?php

namespace MODX\Sources\sqlsrv;

use xPDO\xPDO;

class modAccessMediaSource extends \MODX\Sources\modAccessMediaSource
{

    public static $metaMap = [
        'package' => 'MODX\\Sources',
        'version' => '3.0',
        'table' => 'access_media_source',
        'extends' => 'MODX\\modAccess',
        'fields' =>
            [
                'context_key' => '',
            ],
        'fieldMeta' =>
            [
                'context_key' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'fk',
                    ],
            ],
        'indexes' =>
            [
                'context_key' =>
                    [
                        'alias' => 'context_key',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
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
                'Target' =>
                    [
                        'class' => 'MODX\\Sources\\modMediaSource',
                        'local' => 'target',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
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
