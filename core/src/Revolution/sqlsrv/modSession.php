<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modSession extends \MODX\Revolution\modSession
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'session',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' => 
        array (
            'id' => '',
            'access' => NULL,
            'data' => NULL,
        ),
        'fieldMeta' => 
        array (
            'id' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'index' => 'pk',
                'default' => '',
            ),
            'access' => 
            array (
                'dbtype' => 'bigint',
                'phptype' => 'timestamp',
                'null' => false,
            ),
            'data' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
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
                    'id' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'access' => 
            array (
                'alias' => 'access',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'access' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
        'validation' => 
        array (
            'rules' => 
            array (
                'id' => 
                array (
                    'invalid' => 
                    array (
                        'type' => 'preg_match',
                        'rule' => '/^[0-9a-zA-Z,-]{22,255}$/',
                        'message' => 'session_err_invalid_id',
                    ),
                ),
            ),
        ),
    );

}
