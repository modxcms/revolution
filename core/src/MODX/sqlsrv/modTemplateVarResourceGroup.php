<?php

namespace MODX\sqlsrv;


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
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'documentgroup' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
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
