<?php

namespace MODX\sqlsrv;


class modNamespace extends \MODX\modNamespace
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'namespaces',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'name' => '',
                'path' => '',
                'assets_path' => '',
            ],
        'fieldMeta' =>
            [
                'name' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '40',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'pk',
                    ],
                'path' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                        'default' => '',
                    ],
                'assets_path' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                        'default' => '',
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
                                'name' =>
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
                'LexiconEntries' =>
                    [
                        'class' => 'MODX\\modLexiconEntry',
                        'local' => 'name',
                        'foreign' => 'namespace',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'SystemSettings' =>
                    [
                        'class' => 'MODX\\modSystemSetting',
                        'local' => 'name',
                        'foreign' => 'namespace',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'ContextSettings' =>
                    [
                        'class' => 'MODX\\modContextSetting',
                        'local' => 'name',
                        'foreign' => 'namespace',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'UserSettings' =>
                    [
                        'class' => 'MODX\\modUserSetting',
                        'local' => 'name',
                        'foreign' => 'namespace',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'Actions' =>
                    [
                        'class' => 'MODX\\modAction',
                        'local' => 'name',
                        'foreign' => 'namespace',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
    ];
}
