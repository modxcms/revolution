<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modSnippet extends \MODX\Revolution\modSnippet
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'site_snippets',
        'extends' => 'MODX\\Revolution\\modScript',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'cache_type' => 0,
            'snippet' => NULL,
            'locked' => 0,
            'properties' => NULL,
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
            'snippet' => 
            array (
                'dbtype' => 'mediumtext',
                'phptype' => 'string',
            ),
            'locked' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'attributes' => 'unsigned',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'properties' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'array',
                'null' => true,
            ),
            'moduleguid' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '32',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'fk',
            ),
            'static' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'attributes' => 'unsigned',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'static_file' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
        ),
        'fieldAliases' => 
        array (
            'content' => 'snippet',
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
            'moduleguid' => 
            array (
                'alias' => 'moduleguid',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'moduleguid' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'static' => 
            array (
                'alias' => 'static',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'static' => 
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
                        'element_class' => 'MODX\\Revolution\\modSnippet',
                    ),
                ),
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
                        'rule' => '/^(?!\\s)[a-zA-Z0-9\\x2d-\\x2f\\x7f-\\xff-_\\s]+(?<!\\s)$/',
                        'message' => 'snippet_err_invalid_name',
                    ),
                ),
            ),
        ),
    );

}
