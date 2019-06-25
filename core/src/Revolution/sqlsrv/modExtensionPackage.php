<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modExtensionPackage extends \MODX\Revolution\modExtensionPackage
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'extension_packages',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' => 
        array (
            'namespace' => 'core',
            'path' => '',
            'table_prefix' => '',
            'service_class' => '',
            'service_name' => '',
        ),
        'fieldMeta' => 
        array (
            'namespace' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '40',
                'phptype' => 'string',
                'null' => false,
                'default' => 'core',
                'index' => 'index',
            ),
            'path' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'table_prefix' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'service_class' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'service_name' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
        ),
        'indexes' => 
        array (
            'namespace' => 
            array (
                'alias' => 'namespace',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'namespace' => 
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
            'Namespace' => 
            array (
                'class' => 'MODX\\Revolution\\modNamespace',
                'local' => 'namespace',
                'foreign' => 'name',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
