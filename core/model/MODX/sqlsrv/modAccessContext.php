<?php

namespace MODX\sqlsrv;


class modAccessContext extends \MODX\modAccessContext
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'access_context',
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
                        'class' => 'MODX\\modContext',
                        'local' => 'target',
                        'foreign' => 'key',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
