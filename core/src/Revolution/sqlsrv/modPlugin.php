<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modPlugin extends \MODX\Revolution\modPlugin
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'site_plugins',
        'extends' => 'MODX\\Revolution\\modScript',
        'fields' => 
        array (
            'cache_type' => 0,
            'plugincode' => '',
            'locked' => 0,
            'properties' => NULL,
            'disabled' => 0,
            'moduleguid' => '',
            'static' => 0,
            'static_file' => '',
        ),
        'fieldMeta' => 
        array (
            'cache_type' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'plugincode' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'locked' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'properties' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'array',
                'null' => true,
            ),
            'disabled' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'moduleguid' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '32',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'fk',
            ),
            'static' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'static_file' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
        ),
        'fieldAliases' => 
        array (
            'content' => 'plugincode',
        ),
        'indexes' => 
        array (
            'locked' => 
            array (
                'alias' => 'locked',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'locked' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'disabled' => 
            array (
                'alias' => 'disabled',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'disabled' => 
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
            'PropertySets' => 
            array (
                'class' => 'MODX\\Revolution\\modElementPropertySet',
                'local' => 'id',
                'foreign' => 'element',
                'owner' => 'local',
                'cardinality' => 'many',
                'criteria' => 
                array (
                    'foreign' => 
                    array (
                        'element_class' => 'MODX\\Revolution\\modPlugin',
                    ),
                ),
            ),
            'PluginEvents' => 
            array (
                'class' => 'MODX\\Revolution\\modPluginEvent',
                'local' => 'id',
                'foreign' => 'pluginid',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
        'validation' => 
        array (
            'rules' => 
            array (
                'name' => 
                array (
                    'invalid' => 
                    array (
                        'type' => 'preg_match',
                        'rule' => '/^(?!\\s)[a-zA-Z0-9_-\\x7f-\\xff\\s]+(?!\\s)$/',
                        'message' => 'plugin_err_invalid_name',
                    ),
                ),
            ),
        ),
    );

}
