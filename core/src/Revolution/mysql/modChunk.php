<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modChunk extends \MODX\Revolution\modChunk
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'site_htmlsnippets',
        'extends' => 'MODX\\Revolution\\modElement',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'name' => '',
            'description' => 'Chunk',
            'editor_type' => 0,
            'category' => 0,
            'cache_type' => 0,
            'snippet' => NULL,
            'locked' => 0,
            'properties' => NULL,
            'static' => 0,
            'static_file' => '',
        ),
        'fieldMeta' => 
        array (
            'name' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '50',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'unique',
            ),
            'description' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => 'Chunk',
            ),
            'editor_type' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'category' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'fk',
            ),
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
            'category' => 
            array (
                'alias' => 'category',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'category' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
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
                        'element_class' => 'MODX\\Revolution\\modChunk',
                    ),
                ),
            ),
        ),
        'aggregates' => 
        array (
            'Category' => 
            array (
                'class' => 'MODX\\Revolution\\modCategory',
                'key' => 'id',
                'local' => 'category',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
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
                        'message' => 'chunk_err_invalid_name',
                    ),
                ),
            ),
        ),
    );

}
