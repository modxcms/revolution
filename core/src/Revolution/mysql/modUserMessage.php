<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modUserMessage extends \MODX\Revolution\modUserMessage
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'user_messages',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'type' => '',
            'subject' => '',
            'message' => '',
            'sender' => 0,
            'recipient' => 0,
            'private' => 0,
            'date_sent' => NULL,
            'read' => 0,
        ),
        'fieldMeta' => 
        array (
            'type' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '15',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'subject' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'message' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'sender' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'recipient' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'private' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '4',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'date_sent' => 
            array (
                'dbtype' => 'datetime',
                'phptype' => 'datetime',
                'null' => true,
                'default' => NULL,
            ),
            'read' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
        ),
        'aggregates' => 
        array (
            'Sender' => 
            array (
                'class' => 'MODX\\Revolution\\modUser',
                'local' => 'sender',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'Recipient' => 
            array (
                'class' => 'MODX\\Revolution\\modUser',
                'local' => 'recipient',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
