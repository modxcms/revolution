<?php

namespace MODX\mysql;


class modNamespace extends \MODX\modNamespace
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'namespaces',
        'extends' => 'MODX\\modAccessibleObject',
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
                        'dbtype' => 'varchar',
                        'precision' => '40',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'pk',
                    ],
                'path' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'string',
                        'default' => '',
                    ],
                'assets_path' =>
                    [
                        'dbtype' => 'text',
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
                'ExtensionPackages' =>
                    [
                        'class' => 'MODX\\modExtensionPackage',
                        'local' => 'name',
                        'foreign' => 'namespace',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'Acls' =>
                    [
                        'class' => 'MODX\\modAccessNamespace',
                        'local' => 'name',
                        'foreign' => 'target',
                        'owner' => 'local',
                        'cardinality' => 'many',
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
