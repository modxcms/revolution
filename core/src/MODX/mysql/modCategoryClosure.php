<?php

namespace MODX\mysql;


class modCategoryClosure extends \MODX\modCategoryClosure
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'categories_closure',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'ancestor' => 0,
                'descendant' => 0,
                'depth' => 0,
            ],
        'fieldMeta' =>
            [
                'ancestor' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'attributes' => 'unsigned',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'descendant' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'attributes' => 'unsigned',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'depth' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'attributes' => 'unsigned',
                        'null' => false,
                        'default' => 0,
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
                                'ancestor' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'descendant' =>
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
                'Ancestor' =>
                    [
                        'class' => 'MODX\\modCategory',
                        'local' => 'ancestor',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Descendant' =>
                    [
                        'class' => 'MODX\\modCategory',
                        'local' => 'descendant',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
