<?php
namespace MODX\Revolution\Sources\mysql;

use xPDO\xPDO;

class modMediaSource extends \MODX\Revolution\Sources\modMediaSource
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\Sources',
        'version' => '3.0',
        'table' => 'media_sources',
        'extends' => 'MODX\\Revolution\\modAccessibleSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'name' => '',
            'description' => NULL,
            'class_key' => 'MODX\\Revolution\\Sources\\modFileMediaSource',
            'properties' => NULL,
            'is_stream' => 1,
        ),
        'fieldMeta' => 
        array (
            'name' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'index',
            ),
            'description' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
                'null' => true,
            ),
            'class_key' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => 'MODX\\Revolution\\Sources\\modFileMediaSource',
                'index' => 'index',
            ),
            'properties' => 
            array (
                'dbtype' => 'mediumtext',
                'phptype' => 'array',
                'null' => true,
            ),
            'is_stream' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'attributes' => 'unsigned',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 1,
                'index' => 'index',
            ),
        ),
        'indexes' => 
        array (
            'name' => 
            array (
                'alias' => 'name',
                'primary' => false,
                'unique' => false,
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
            'class_key' => 
            array (
                'alias' => 'class_key',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'class_key' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'is_stream' => 
            array (
                'alias' => 'is_stream',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'is_stream' => 
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
            'SourceElement' => 
            array (
                'class' => 'MODX\\Revolution\\Sources\\modMediaSourceElement',
                'local' => 'id',
                'foreign' => 'source',
                'cardinality' => 'one',
                'owner' => 'local',
            ),
        ),
        'aggregates' => 
        array (
            'Chunks' => 
            array (
                'class' => 'MODX\\Revolution\\modChunk',
                'local' => 'id',
                'foreign' => 'source',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'Plugins' => 
            array (
                'class' => 'MODX\\Revolution\\modPlugin',
                'local' => 'id',
                'foreign' => 'source',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'Snippets' => 
            array (
                'class' => 'MODX\\Revolution\\modSnippet',
                'local' => 'id',
                'foreign' => 'source',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'Templates' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplate',
                'local' => 'id',
                'foreign' => 'source',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'TemplateVars' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplateVar',
                'local' => 'id',
                'foreign' => 'source',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
    );

}
