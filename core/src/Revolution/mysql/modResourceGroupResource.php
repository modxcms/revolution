<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modResourceGroupResource extends \MODX\Revolution\modResourceGroupResource
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'document_groups',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'document_group' => 0,
            'document' => 0,
        ),
        'fieldMeta' => 
        array (
            'document_group' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'document' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
        ),
        'indexes' => 
        array (
            'document_group' => 
            array (
                'alias' => 'document_group',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'document_group' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'document' => 
            array (
                'alias' => 'document',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'document' => 
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
            'ResourceGroup' => 
            array (
                'class' => 'MODX\\Revolution\\modResourceGroup',
                'key' => 'id',
                'local' => 'document_group',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'Resource' => 
            array (
                'class' => 'MODX\\Revolution\\modResource',
                'key' => 'id',
                'local' => 'document',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
