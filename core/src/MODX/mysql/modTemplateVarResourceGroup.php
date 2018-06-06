<?php

namespace MODX\mysql;


class modTemplateVarResourceGroup extends \MODX\modTemplateVarResourceGroup
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'site_tmplvar_access',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'tmplvarid' => 0,
                'documentgroup' => 0,
            ],
        'fieldMeta' =>
            [
                'tmplvarid' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'documentgroup' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
            ],
        'indexes' =>
            [
                'tmplvar_template' =>
                    [
                        'alias' => 'tmplvar_template',
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'tmplvarid' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'documentgroup' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'aggregates' =>
            [
                'TemplateVar' =>
                    [
                        'class' => 'MODX\\modTemplateVar',
                        'local' => 'tmplvarid',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'ResourceGroup' =>
                    [
                        'class' => 'MODX\\modResourceGroup',
                        'local' => 'documentgroup',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
