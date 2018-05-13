<?php

namespace MODX\sqlsrv;


class modAccessAction extends \MODX\modAccessAction
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'access_actions',
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
                        'class' => 'MODX\\modAction',
                        'local' => 'target',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
