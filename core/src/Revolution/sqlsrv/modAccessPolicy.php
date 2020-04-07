<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modAccessPolicy extends \MODX\Revolution\modAccessPolicy
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'access_policies',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' => 
        array (
            'name' => NULL,
            'description' => NULL,
            'parent' => 0,
            'template' => 0,
            'class' => '',
            'data' => '{}',
            'lexicon' => 'permissions',
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
            'description' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
            ),
            'parent' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'template' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'class' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'index',
            ),
            'data' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'json',
                'default' => '{}',
            ),
            'lexicon' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => 'permissions',
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
            'parent' => 
            array (
                'alias' => 'parent',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'parent' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'class' => 
            array (
                'alias' => 'class',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'class' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'template' => 
            array (
                'alias' => 'template',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'template' => 
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
            'Children' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessPolicy',
                'local' => 'id',
                'foreign' => 'parent',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
        ),
        'aggregates' => 
        array (
            'Parent' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessPolicy',
                'local' => 'parent',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
            'Template' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessPolicyTemplate',
                'local' => 'template',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
        ),
    );

}
