<?php

namespace MODX\sqlsrv;


class modElement extends \MODX\modElement
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'site_element',
        'extends' => 'MODX\\modAccessibleSimpleObject',
        'fields' =>
            [
                'source' => 0,
                'property_preprocess' => 0,
            ],
        'fieldMeta' =>
            [
                'source' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'fk',
                    ],
                'property_preprocess' =>
                    [
                        'dbtype' => 'bit',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                    ],
            ],
        'composites' =>
            [
                'Acls' =>
                    [
                        'class' => 'MODX\\modAccessElement',
                        'local' => 'id',
                        'foreign' => 'target',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
            ],
        'aggregates' =>
            [
                'CategoryAcls' =>
                    [
                        'class' => 'MODX\\modAccessCategory',
                        'local' => 'category',
                        'foreign' => 'target',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
                'Source' =>
                    [
                        'class' => 'MODX\\Sources\\modMediaSource',
                        'local' => 'source',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
