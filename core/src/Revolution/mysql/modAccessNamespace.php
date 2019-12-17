<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modAccessNamespace extends \MODX\Revolution\modAccessNamespace
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'access_namespace',
        'extends' => 'MODX\\Revolution\\modAccess',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'context_key' => '',
        ),
        'fieldMeta' => 
        array (
            'context_key' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'fk',
            ),
        ),
        'indexes' => 
        array (
            'context_key' => 
            array (
                'alias' => 'context_key',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'context_key' => 
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
            'Target' => 
            array (
                'class' => 'MODX\\Revolution\\modNamespace',
                'local' => 'target',
                'foreign' => 'name',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
            'Context' => 
            array (
                'class' => 'MODX\\Revolution\\modContext',
                'local' => 'context_key',
                'foreign' => 'key',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
