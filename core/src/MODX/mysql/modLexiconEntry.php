<?php

namespace MODX\mysql;


class modLexiconEntry extends \MODX\modLexiconEntry
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'lexicon_entries',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'name' => '',
                'value' => '',
                'topic' => 'default',
                'namespace' => 'core',
                'language' => 'en',
                'createdon' => null,
                'editedon' => null,
            ],
        'fieldMeta' =>
            [
                'name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'value' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'topic' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'default',
                        'index' => 'index',
                    ],
                'namespace' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '40',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'core',
                        'index' => 'index',
                    ],
                'language' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '20',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'en',
                        'index' => 'index',
                    ],
                'createdon' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'datetime',
                    ],
                'editedon' =>
                    [
                        'dbtype' => 'timestamp',
                        'phptype' => 'timestamp',
                        'null' => true,
                        'default' => null,
                        'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
                    ],
            ],
        'indexes' =>
            [
                'name' =>
                    [
                        'alias' => 'name',
                        'primary' => false,
                        'unique' => false,
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
                'topic' =>
                    [
                        'alias' => 'topic',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'topic' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
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
                'language' =>
                    [
                        'alias' => 'language',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'language' =>
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
                'Namespace' =>
                    [
                        'class' => 'MODX\\modNamespace',
                        'local' => 'namespace',
                        'foreign' => 'name',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
