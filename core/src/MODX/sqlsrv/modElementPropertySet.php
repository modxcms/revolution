<?php

namespace MODX\sqlsrv;


class modElementPropertySet extends \MODX\modElementPropertySet
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'element_property_sets',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'element' => 0,
                'element_class' => '',
                'property_set' => 0,
            ],
        'fieldMeta' =>
            [
                'element' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'element_class' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'pk',
                    ],
                'property_set' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
            ],
        'indexes' =>
            [
                'PRIMARY' =>
                    [
                        'alias' => 'PRIMARY',
                        'primary' => true,
                        'unique' => true,
                        'columns' =>
                            [
                                'element' =>
                                    [
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'element_class' =>
                                    [
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'property_set' =>
                                    [
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'aggregates' =>
            [
                'Element' =>
                    [
                        'class' => 'MODX\\modElement',
                        'local' => 'element',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
                'PropertySet' =>
                    [
                        'class' => 'MODX\\modPropertySet',
                        'local' => 'property_set',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
