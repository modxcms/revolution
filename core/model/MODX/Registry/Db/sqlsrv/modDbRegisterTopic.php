<?php

namespace MODX\Registry\Db\sqlsrv;

use xPDO\xPDO;

class modDbRegisterTopic extends \MODX\Registry\Db\modDbRegisterTopic
{

    public static $metaMap = [
        'package' => 'MODX\\Registry\\Db',
        'version' => '3.0',
        'table' => 'register_topics',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'queue' => null,
                'name' => null,
                'created' => null,
                'updated' => null,
                'options' => null,
            ],
        'fieldMeta' =>
            [
                'queue' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'index' => 'fk',
                    ],
                'name' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'fk',
                    ],
                'created' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'datetime',
                        'null' => false,
                    ],
                'updated' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'timestamp',
                    ],
                'options' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'array',
                    ],
            ],
        'indexes' =>
            [
                'queue' =>
                    [
                        'alias' => 'queue',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'queue' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
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
            ],
        'composites' =>
            [
                'Messages' =>
                    [
                        'class' => 'MODX\\Registry\\Db\\modDbRegisterMessage',
                        'local' => 'id',
                        'foreign' => 'topic',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
        'aggregates' =>
            [
                'Queue' =>
                    [
                        'class' => 'MODX\\Registry\\Db\\modDbRegisterQueue',
                        'local' => 'queue',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
