<?php

namespace MODX\Registry\Db\sqlsrv;

use xPDO\Om\xPDOCriteria;
use MODX\Registry\modDbRegister;

class modDbRegisterMessage extends \MODX\Registry\Db\modDbRegisterMessage
{

    public static $metaMap = [
        'package' => 'MODX\\Registry\\Db',
        'version' => '3.0',
        'table' => 'register_messages',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'topic' => null,
                'id' => null,
                'created' => null,
                'valid' => null,
                'accessed' => null,
                'accesses' => 0,
                'expires' => '0',
                'payload' => null,
                'kill' => 0,
            ],
        'fieldMeta' =>
            [
                'topic' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'index' => 'pk',
                    ],
                'id' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'pk',
                    ],
                'created' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'datetime',
                        'null' => false,
                        'index' => 'index',
                    ],
                'valid' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'datetime',
                        'null' => false,
                        'index' => 'index',
                    ],
                'accessed' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'timestamp',
                        'index' => 'index',
                    ],
                'accesses' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'expires' =>
                    [
                        'dbtype' => 'integer',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => '0',
                        'index' => 'index',
                    ],
                'payload' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                        'null' => false,
                    ],
                'kill' =>
                    [
                        'dbtype' => 'bit',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
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
                                'topic' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'id' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'created' =>
                    [
                        'alias' => 'created',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'created' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'valid' =>
                    [
                        'alias' => 'valid',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'valid' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'accessed' =>
                    [
                        'alias' => 'accessed',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'accessed' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'accesses' =>
                    [
                        'alias' => 'accesses',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'accesses' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'expires' =>
                    [
                        'alias' => 'expires',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'expires' =>
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
                'Topic' =>
                    [
                        'class' => 'MODX\\Registry\\Db\\modDbRegisterTopic',
                        'local' => 'topic',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];


    public static function getValidMessages(modDbRegister &$register, $topic, $topicBase, $topicMsg, $limit, array $options = [])
    {
        $messages = [];
        $fetchMode = isset($options['fetchMode']) ? $options['fetchMode'] : \PDO::FETCH_OBJ;
        $msgTable = $register->modx->getTableName('registry.db.modDbRegisterMessage');
        $topicTable = $register->modx->getTableName('registry.db.modDbRegisterTopic');
        $limitClause = $limit > 0 ? "TOP {$limit}" : '';
        $query = new xPDOCriteria(
            $register->modx,
            "SELECT {$limitClause} msg.* FROM {$msgTable} msg JOIN {$topicTable} topic ON msg.valid <= :now AND (topic.name = :topic OR (topic.name = :topicbase AND msg.id = :topicmsg)) AND topic.id = msg.topic ORDER BY msg.created ASC",
            [
                ':now' => strftime('%Y-%m-%d %H:%M:%S'),
                ':topic' => $topic,
                ':topicbase' => $topicBase,
                ':topicmsg' => $topicMsg,
            ]
        );
        if ($query->stmt && $query->stmt->execute()) {
            $messages = $query->stmt->fetchAll($fetchMode);
        }

        return $messages;
    }
}
