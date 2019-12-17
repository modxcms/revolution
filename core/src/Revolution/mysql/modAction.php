<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modAction extends \MODX\Revolution\modAction
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'actions',
        'extends' => 'MODX\\Revolution\\modAccessibleSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'namespace' => 'core',
            'controller' => NULL,
            'haslayout' => 1,
            'lang_topics' => NULL,
            'assets' => '',
            'help_url' => '',
        ),
        'fieldMeta' => 
        array (
            'namespace' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => 'core',
                'index' => 'index',
            ),
            'controller' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'index' => 'index',
            ),
            'haslayout' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 1,
            ),
            'lang_topics' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
                'null' => false,
            ),
            'assets' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'help_url' => 
            array (
                'dbtype' => 'text',
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
            'controller' => 
            array (
                'alias' => 'controller',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'controller' => 
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
            'Menus' => 
            array (
                'class' => 'MODX\\Revolution\\modMenu',
                'local' => 'id',
                'foreign' => 'action',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
            'Acls' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessAction',
                'local' => 'id',
                'foreign' => 'target',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
            'Fields' => 
            array (
                'class' => 'MODX\\Revolution\\modActionField',
                'local' => 'id',
                'foreign' => 'action',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
            'DOM' => 
            array (
                'class' => 'MODX\\Revolution\\modActionDom',
                'local' => 'id',
                'foreign' => 'action',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
        ),
        'aggregates' => 
        array (
            'Namespace' => 
            array (
                'class' => 'MODX\\Revolution\\modNamespace',
                'local' => 'namespace',
                'foreign' => 'name',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
        ),
    );

}
