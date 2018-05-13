<?php

namespace MODX\mysql;


class modContextResource extends \MODX\modContextResource
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'context_resource',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'context_key' => null,
                'resource' => null,
            ],
        'fieldMeta' =>
            [
                'context_key' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'pk',
                    ],
                'resource' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '11',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
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
                        'columns' =>
                            [
                                'context_key' =>
                                    [
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'resource' =>
                                    [
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'aggregates' =>
            [
                'Context' =>
                    [
                        'class' => 'MODX\\modContext',
                        'local' => 'context_key',
                        'foreign' => 'key',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Resource' =>
                    [
                        'class' => 'MODX\\modResource',
                        'local' => 'resource',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
