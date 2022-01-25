<?php
namespace MODX\Revolution\Registry\Db\mysql;

use MODX\Revolution\Registry\modDbRegister;
use PDO;
use xPDO\Om\xPDOCriteria;

class modDbRegisterMessage extends \MODX\Revolution\Registry\Db\modDbRegisterMessage
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\Registry\\Db',
        'version' => '3.0',
        'table' => 'register_messages',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'tableMeta' =>
        array (
            'engine' => 'InnoDB',
        ),
        'fields' =>
        array (
            'topic' => NULL,
            'id' => NULL,
            'created' => NULL,
            'valid' => NULL,
            'accessed' => NULL,
            'accesses' => 0,
            'expires' => 0,
            'payload' => NULL,
            'kill' => 0,
        ),
        'fieldMeta' =>
        array (
            'topic' =>
            array (
                'dbtype' => 'integer',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'index' => 'pk',
            ),
            'id' =>
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'index' => 'pk',
            ),
            'created' =>
            array (
                'dbtype' => 'datetime',
                'phptype' => 'datetime',
                'null' => false,
                'index' => 'index',
            ),
            'valid' =>
            array (
                'dbtype' => 'datetime',
                'phptype' => 'datetime',
                'null' => false,
                'index' => 'index',
            ),
            'accessed' =>
            array (
                'dbtype' => 'timestamp',
                'phptype' => 'timestamp',
                'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
                'index' => 'index',
            ),
            'accesses' =>
            array (
                'dbtype' => 'integer',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'expires' =>
            array (
                'dbtype' => 'integer',
                'precision' => '20',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'payload' =>
            array (
                'dbtype' => 'mediumtext',
                'phptype' => 'string',
                'null' => false,
            ),
            'kill' =>
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'attributes' => 'unsigned',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
            ),
        ),
        'indexes' =>
        array (
            'PRIMARY' =>
            array (
                'alias' => 'PRIMARY',
                'primary' => true,
                'unique' => true,
                'type' => 'BTREE',
                'columns' =>
                array (
                    'topic' =>
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'id' =>
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'created' =>
            array (
                'alias' => 'created',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' =>
                array (
                    'created' =>
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'valid' =>
            array (
                'alias' => 'valid',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' =>
                array (
                    'valid' =>
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'accessed' =>
            array (
                'alias' => 'accessed',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' =>
                array (
                    'accessed' =>
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'accesses' =>
            array (
                'alias' => 'accesses',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' =>
                array (
                    'accesses' =>
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'expires' =>
            array (
                'alias' => 'expires',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' =>
                array (
                    'expires' =>
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
        'aggregates' =>
        array (
            'Topic' =>
            array (
                'class' => 'MODX\\Revolution\\Registry\\Db\\modDbRegisterTopic',
                'local' => 'topic',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

    public static function getValidMessages(
        modDbRegister &$register,
        $topic,
        $topicBase,
        $topicMsg,
        $limit,
        array $options = []
    ) {
        $messages = [];
        $fetchMode = isset($options['fetchMode']) ? $options['fetchMode'] : PDO::FETCH_OBJ;
        $msgTable = $register->modx->getTableName(\MODX\Revolution\Registry\Db\modDbRegisterMessage::class);
        $topicTable = $register->modx->getTableName(\MODX\Revolution\Registry\Db\modDbRegisterTopic::class);
        $limitClause = $limit > 0 ? "LIMIT {$limit}" : '';
        $query = new xPDOCriteria(
            $register->modx,
            "SELECT msg.* FROM {$msgTable} msg JOIN {$topicTable} topic ON msg.valid <= :now AND (topic.name = :topic OR (topic.name = :topicbase AND msg.id = :topicmsg)) AND topic.id = msg.topic ORDER BY msg.created ASC {$limitClause}",
            [
                ':now' => date('Y-m-d H:i:s'),
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
