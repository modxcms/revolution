<?php
namespace MODX\Revolution\Registry\Db\sqlsrv;

use xPDO\xPDO;

class modDbRegisterTopic extends \MODX\Revolution\Registry\Db\modDbRegisterTopic
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\Registry\\Db',
        'version' => '3.0',
        'table' => 'register_topics',
        'extends' => 'MODX\\Revolution\\xPDOSimpleObject',
        'fields' => 
        array (
            'queue' => NULL,
            'name' => NULL,
            'created' => NULL,
            'updated' => NULL,
            'options' => NULL,
        ),
        'fieldMeta' => 
        array (
            'queue' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'index' => 'fk',
            ),
            'name' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'index' => 'fk',
            ),
            'created' => 
            array (
                'dbtype' => 'datetime',
                'phptype' => 'datetime',
                'null' => false,
            ),
            'updated' => 
            array (
                'dbtype' => 'datetime',
                'phptype' => 'timestamp',
            ),
            'options' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'array',
            ),
        ),
        'indexes' => 
        array (
            'queue' => 
            array (
                'alias' => 'queue',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'queue' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'name' => 
            array (
                'alias' => 'name',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'name' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
        'composites' => 
        array (
            'Messages' => 
            array (
                'class' => 'MODX\\Revolution\\Registry\\Db\\modDbRegisterMessage',
                'local' => 'id',
                'foreign' => 'topic',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
        'aggregates' => 
        array (
            'Queue' => 
            array (
                'class' => 'MODX\\Revolution\\Registry\\Db\\modDbRegisterQueue',
                'local' => 'queue',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
