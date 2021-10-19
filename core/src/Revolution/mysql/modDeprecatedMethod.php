<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modDeprecatedMethod extends \MODX\Revolution\modDeprecatedMethod
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'deprecated_method',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'definition' => '',
            'since' => '',
            'recommendation' => '',
        ),
        'fieldMeta' => 
        array (
            'definition' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'since' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'recommendation' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '1024',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
        ),
        'indexes' => 
        array (
            'definition' => 
            array (
                'alias' => 'definition',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'definition' => 
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
            'Callers' => 
            array (
                'class' => 'MODX\\Revolution\\modDeprecatedCall',
                'local' => 'id',
                'foreign' => 'method',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
    );

}
