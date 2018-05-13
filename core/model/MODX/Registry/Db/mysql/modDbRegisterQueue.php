<?php

namespace MODX\Registry\Db\mysql;

use xPDO\xPDO;

class modDbRegisterQueue extends \MODX\Registry\Db\modDbRegisterQueue
{

    public static $metaMap = [
        'package' => 'MODX\\Registry\\Db',
        'version' => '3.0',
        'table' => 'register_queues',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'name' => null,
                'options' => null,
            ],
        'fieldMeta' =>
            [
                'name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'unique',
                    ],
                'options' =>
                    [
                        'dbtype' => 'mediumtext',
                        'phptype' => 'array',
                    ],
            ],
        'indexes' =>
            [
                'name' =>
                    [
                        'alias' => 'name',
                        'primary' => false,
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
                'Topics' =>
                    [
                        'class' => 'MODX\\Registry\\Db\\modDbRegisterTopic',
                        'local' => 'id',
                        'foreign' => 'queue',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
    ];
}
