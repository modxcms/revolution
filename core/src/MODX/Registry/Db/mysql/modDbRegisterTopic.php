<?php

namespace MODX\Registry\Db\mysql;

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
                        'dbtype' => 'integer',
                        'precision' => '10',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'index' => 'fk',
                    ],
                'name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
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
                        'dbtype' => 'timestamp',
                        'phptype' => 'timestamp',
                        'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
                    ],
                'options' =>
                    [
                        'dbtype' => 'mediumtext',
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
