<?php

namespace MODX\mysql;


class modTemplateVarResource extends \MODX\modTemplateVarResource
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'site_tmplvar_contentvalues',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'tmplvarid' => 0,
                'contentid' => 0,
                'value' => null,
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
                        'index' => 'index',
                    ],
                'contentid' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'value' =>
                    [
                        'dbtype' => 'mediumtext',
                        'phptype' => 'string',
                        'null' => false,
                    ],
            ],
        'indexes' =>
            [
                'tmplvarid' =>
                    [
                        'alias' => 'tmplvarid',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'tmplvarid' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'contentid' =>
                    [
                        'alias' => 'contentid',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'contentid' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'tv_cnt' =>
                    [
                        'alias' => 'tv_cnt',
                        'primary' => false,
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
                                'contentid' =>
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
                'Resource' =>
                    [
                        'class' => 'MODX\\modResource',
                        'local' => 'contentid',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
