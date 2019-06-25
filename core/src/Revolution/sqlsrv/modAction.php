<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modAction extends \MODX\Revolution\modAction
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'actions',
        'extends' => 'MODX\\Revolution\\modAccessibleSimpleObject',
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
                'dbtype' => 'nvarchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => 'core',
                'index' => 'index',
            ),
            'controller' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'index' => 'index',
            ),
            'haslayout' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'phptype' => 'integer',
                'null' => false,
                'default' => 1,
            ),
            'lang_topics' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
                'null' => false,
            ),
            'assets' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'help_url' => 
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
