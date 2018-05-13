<?php

namespace MODX\sqlsrv;


class modAccessibleSimpleObject extends \MODX\modAccessibleSimpleObject
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'extends' => 'MODX\\modAccessibleObject',
        'fields' =>
            [
                'id' => null,
            ],
        'fieldMeta' =>
            [
                'id' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'index' => 'pk',
                        'generated' => 'native',
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
                                'id' =>
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
