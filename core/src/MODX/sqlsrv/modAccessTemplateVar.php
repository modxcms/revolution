<?php

namespace MODX\sqlsrv;


class modAccessTemplateVar extends \MODX\modAccessTemplateVar
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'access_templatevars',
        'extends' => 'MODX\\modAccessElement',
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
                        'class' => 'MODX\\modTemplateVar',
                        'local' => 'target',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
