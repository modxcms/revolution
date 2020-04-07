<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modCategoryClosure extends \MODX\Revolution\modCategoryClosure
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'categories_closure',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' => 
        array (
            'ancestor' => 0,
            'descendant' => 0,
            'depth' => 0,
        ),
        'fieldMeta' => 
        array (
            'ancestor' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'pk',
            ),
            'descendant' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'pk',
            ),
            'depth' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
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
                    'ancestor' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'descendant' => 
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
            'Ancestor' => 
            array (
                'class' => 'MODX\\Revolution\\modCategory',
                'local' => 'ancestor',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'Descendant' => 
            array (
                'class' => 'MODX\\Revolution\\modCategory',
                'local' => 'descendant',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
