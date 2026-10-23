<?php

namespace MODX\Revolution\mysql;

class modAccessContextGroup extends \MODX\Revolution\modAccessContextGroup
{
    public static $metaMap = [
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'access_context_groups',
        'extends' => 'MODX\\Revolution\\modAccess',
        'tableMeta' => [
            'engine' => 'InnoDB',
        ],
        'fields' => [],
        'fieldMeta' => [],
        'aggregates' => [
            'Target' => [
                'class' => 'MODX\\Revolution\\modContextGroup',
                'local' => 'target',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ],
        ],
    ];
}
