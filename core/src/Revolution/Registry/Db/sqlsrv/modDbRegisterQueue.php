<?php
namespace MODX\Revolution\Registry\Db\sqlsrv;

use xPDO\xPDO;

class modDbRegisterQueue extends \MODX\Revolution\Registry\Db\modDbRegisterQueue
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\Registry\\Db',
        'version' => '3.0',
        'table' => 'register_queues',
        'extends' => 'MODX\\Revolution\\xPDOSimpleObject',
        'fields' => 
        array (
            'name' => NULL,
            'options' => NULL,
        ),
        'fieldMeta' => 
        array (
            'name' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'index' => 'unique',
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
            'name' => 
            array (
                'alias' => 'name',
                'primary' => false,
                'unique' => true,
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
            'Topics' => 
            array (
                'class' => 'MODX\\Revolution\\Registry\\Db\\modDbRegisterTopic',
                'local' => 'id',
                'foreign' => 'queue',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
    );

}
