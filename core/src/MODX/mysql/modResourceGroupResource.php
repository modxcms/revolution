<?php

namespace MODX\mysql;


class modResourceGroupResource extends \MODX\modResourceGroupResource
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'document_groups',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'document_group' => 0,
                'document' => 0,
            ],
        'fieldMeta' =>
            [
                'document_group' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'document' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
            ],
        'indexes' =>
            [
                'document_group' =>
                    [
                        'alias' => 'document_group',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'document_group' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'document' =>
                    [
                        'alias' => 'document',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'document' =>
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
                'ResourceGroup' =>
                    [
                        'class' => 'MODX\\modResourceGroup',
                        'key' => 'id',
                        'local' => 'document_group',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Resource' =>
                    [
                        'class' => 'MODX\\modResource',
                        'key' => 'id',
                        'local' => 'document',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
