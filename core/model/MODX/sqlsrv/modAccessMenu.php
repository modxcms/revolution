<?php

namespace MODX\sqlsrv;


class modAccessMenu extends \MODX\modAccessMenu
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'access_menus',
        'extends' => 'MODX\\modAccess',
        'fields' =>
            [
            ],
        'fieldMeta' =>
            [
            ],
        'aggregates' =>
            [
                'Target' =>
                    [
                        'class' => 'MODX\\modMenu',
                        'local' => 'target',
                        'foreign' => 'text',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
