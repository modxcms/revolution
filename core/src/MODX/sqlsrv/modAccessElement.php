<?php

namespace MODX\sqlsrv;


class modAccessElement extends \MODX\modAccessElement
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'access_elements',
        'extends' => 'MODX\\modAccess',
        'fields' =>
            [
                'context_key' => '',
            ],
        'fieldMeta' =>
            [
                'context_key' =>
                    [
                        'dbtype' => 'nvarchar',
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
                        'class' => 'MODX\\modElement',
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
