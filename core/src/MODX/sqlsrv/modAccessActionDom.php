<?php

namespace MODX\sqlsrv;


class modAccessActionDom extends \MODX\modAccessActionDom
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'access_actiondom',
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
                        'class' => 'MODX\\modActionDom',
                        'local' => 'target',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
