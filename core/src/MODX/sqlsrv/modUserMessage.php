<?php

namespace MODX\sqlsrv;


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
                'date_sent' => '0000-00-00 00:00:00',
                'read' => 0,
            ],
        'fieldMeta' =>
            [
                'type' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '15',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'subject' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'message' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'sender' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'recipient' =>
                    [
                        'dbtype' => 'int',
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
                        'null' => false,
                        'default' => '0000-00-00 00:00:00',
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
