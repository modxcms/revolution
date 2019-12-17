<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modContextResource extends \MODX\Revolution\modContextResource
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'context_resource',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'context_key' => NULL,
            'resource' => NULL,
        ),
        'fieldMeta' => 
        array (
            'context_key' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'index' => 'pk',
            ),
            'resource' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'index' => 'pk',
            ),
        ),
        'indexes' => 
        array (
            'PRIMARY' => 
            array (
                'alias' => 'PRIMARY',
                'primary' => true,
                'unique' => true,
                'columns' => 
                array (
                    'context_key' => 
                    array (
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'resource' => 
                    array (
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
        'aggregates' => 
        array (
            'Context' => 
            array (
                'class' => 'MODX\\Revolution\\modContext',
                'local' => 'context_key',
                'foreign' => 'key',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'Resource' => 
            array (
                'class' => 'MODX\\Revolution\\modResource',
                'local' => 'resource',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
