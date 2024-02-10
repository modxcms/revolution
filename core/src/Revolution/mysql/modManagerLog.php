<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modManagerLog extends \MODX\Revolution\modManagerLog
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'manager_log',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'user' => 0,
            'occurred' => 'CURRENT_TIMESTAMP',
            'action' => '',
            'classKey' => '',
            'item' => '0',
        ),
        'fieldMeta' => 
        array (
            'user' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'occurred' => 
            array (
                'dbtype' => 'datetime',
                'phptype' => 'datetime',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
            ),
            'action' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'classKey' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'item' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '0',
            ),
        ),
        'indexes' => 
        array (
            'user_occurred' => 
            array (
                'alias' => 'user_occurred',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'user' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'occurred' => 
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
            'User' => 
            array (
                'class' => 'MODX\\Revolution\\modUser',
                'local' => 'user',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
        ),
    );

}
