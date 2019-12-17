<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modElementPropertySet extends \MODX\Revolution\modElementPropertySet
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'element_property_sets',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'element' => 0,
            'element_class' => '',
            'property_set' => 0,
        ),
        'fieldMeta' => 
        array (
            'element' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'pk',
            ),
            'element_class' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'pk',
            ),
            'property_set' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
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
                    'element' => 
                    array (
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'element_class' => 
                    array (
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'property_set' => 
                    array (
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
        'aggregates' => 
        array (
            'Element' => 
            array (
                'class' => 'MODX\\Revolution\\modElement',
                'local' => 'element',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
            'PropertySet' => 
            array (
                'class' => 'MODX\\Revolution\\modPropertySet',
                'local' => 'property_set',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
        ),
    );

}
