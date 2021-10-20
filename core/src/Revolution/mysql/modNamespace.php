<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modNamespace extends \MODX\Revolution\modNamespace
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'namespaces',
        'extends' => 'MODX\\Revolution\\modAccessibleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'name' => '',
            'path' => '',
            'assets_path' => '',
        ),
        'fieldMeta' => 
        array (
            'name' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '40',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'pk',
            ),
            'path' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
                'default' => '',
            ),
            'assets_path' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
                'default' => '',
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
                    'name' => 
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
            'LexiconEntries' => 
            array (
                'class' => 'MODX\\Revolution\\modLexiconEntry',
                'local' => 'name',
                'foreign' => 'namespace',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'SystemSettings' => 
            array (
                'class' => 'MODX\\Revolution\\modSystemSetting',
                'local' => 'name',
                'foreign' => 'namespace',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'ContextSettings' => 
            array (
                'class' => 'MODX\\Revolution\\modContextSetting',
                'local' => 'name',
                'foreign' => 'namespace',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'UserSettings' => 
            array (
                'class' => 'MODX\\Revolution\\modUserSetting',
                'local' => 'name',
                'foreign' => 'namespace',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'ExtensionPackages' => 
            array (
                'class' => 'MODX\\Revolution\\modExtensionPackage',
                'local' => 'name',
                'foreign' => 'namespace',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'Acls' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessNamespace',
                'local' => 'name',
                'foreign' => 'target',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
        ),
    );

}
