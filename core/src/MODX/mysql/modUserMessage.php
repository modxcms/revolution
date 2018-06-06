<?php

namespace MODX\mysql;


class modUserMessage extends \MODX\modUserMessage
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'user_messages',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'type' => '',
                'subject' => '',
                'message' => '',
                'sender' => 0,
                'recipient' => 0,
                'private' => 0,
                'date_sent' => null,
                'read' => 0,
            ],
        'fieldMeta' =>
            [
                'type' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '15',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'subject' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'message' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'sender' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'recipient' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'private' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '4',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'date_sent' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'datetime',
                        'null' => true,
                        'default' => null,
                    ],
                'read' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
            ],
        'aggregates' =>
            [
                'Sender' =>
                    [
                        'class' => 'MODX\\modUser',
                        'local' => 'sender',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Recipient' =>
                    [
                        'class' => 'MODX\\modUser',
                        'local' => 'recipient',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
