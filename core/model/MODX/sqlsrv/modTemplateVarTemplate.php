<?php

namespace MODX\sqlsrv;


class modTemplateVarTemplate extends \MODX\modTemplateVarTemplate
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'site_tmplvar_templates',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'tmplvarid' => 0,
                'templateid' => 0,
                'rank' => 0,
            ],
        'fieldMeta' =>
            [
                'tmplvarid' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'templateid' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'rank' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
            ],
        'indexes' =>
            [
                'PRIMARY' =>
                    [
                        'alias' => 'PRIMARY',
                        'primary' => true,
                        'unique' => true,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'tmplvarid' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'templateid' =>
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
                        'key' => 'id',
                        'local' => 'tmplvarid',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Template' =>
                    [
                        'class' => 'MODX\\modTemplate',
                        'key' => 'id',
                        'local' => 'templateid',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
