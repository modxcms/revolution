<?php

namespace MODX\mysql;


class modAction extends \MODX\modAction
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'actions',
        'extends' => 'MODX\\modAccessibleSimpleObject',
        'fields' =>
            [
                'namespace' => 'core',
                'controller' => null,
                'haslayout' => 1,
                'lang_topics' => null,
                'assets' => '',
                'help_url' => '',
            ],
        'fieldMeta' =>
            [
                'namespace' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'core',
                        'index' => 'index',
                    ],
                'controller' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'index',
                    ],
                'haslayout' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 1,
                    ],
                'lang_topics' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'string',
                        'null' => false,
                    ],
                'assets' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'help_url' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
            ],
        'indexes' =>
            [
                'namespace' =>
                    [
                        'alias' => 'namespace',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'namespace' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'controller' =>
                    [
                        'alias' => 'controller',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'controller' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'composites' =>
            [
                'Menus' =>
                    [
                        'class' => 'MODX\\modMenu',
                        'local' => 'id',
                        'foreign' => 'action',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
                'Acls' =>
                    [
                        'class' => 'MODX\\modAccessAction',
                        'local' => 'id',
                        'foreign' => 'target',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
                'Fields' =>
                    [
                        'class' => 'MODX\\modActionField',
                        'local' => 'id',
                        'foreign' => 'action',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
                'DOM' =>
                    [
                        'class' => 'MODX\\modActionDom',
                        'local' => 'id',
                        'foreign' => 'action',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
            ],
        'aggregates' =>
            [
                'Namespace' =>
                    [
                        'class' => 'MODX\\modNamespace',
                        'local' => 'namespace',
                        'foreign' => 'name',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
