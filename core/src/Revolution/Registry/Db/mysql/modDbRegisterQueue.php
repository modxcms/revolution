<?php
namespace MODX\Revolution\Registry\Db\mysql;

use xPDO\xPDO;

class modDbRegisterQueue extends \MODX\Revolution\Registry\Db\modDbRegisterQueue
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\Registry\\Db',
        'version' => '3.0',
        'table' => 'register_queues',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'name' => NULL,
            'options' => NULL,
        ),
        'fieldMeta' => 
        array (
            'name' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'index' => 'unique',
            ),
            'options' => 
            array (
                'dbtype' => 'mediumtext',
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
