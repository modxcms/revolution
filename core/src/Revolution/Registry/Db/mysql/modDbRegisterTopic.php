<?php
namespace MODX\Revolution\Registry\Db\mysql;

use xPDO\xPDO;

class modDbRegisterTopic extends \MODX\Revolution\Registry\Db\modDbRegisterTopic
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\Registry\\Db',
        'version' => '3.0',
        'table' => 'register_topics',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
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
                'dbtype' => 'integer',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'index' => 'fk',
            ),
            'name' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
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
                'dbtype' => 'timestamp',
                'phptype' => 'timestamp',
                'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
            ),
            'options' => 
            array (
                'dbtype' => 'mediumtext',
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
