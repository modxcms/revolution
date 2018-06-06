<?php

namespace MODX\mysql;


class modPrincipal extends \MODX\modPrincipal
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
            ],
        'fieldMeta' =>
            [
            ],
        'composites' =>
            [
                'Acls' =>
                    [
                        'class' => 'MODX\\modAccess',
                        'local' => 'id',
                        'foreign' => 'principal',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
    ];
}
