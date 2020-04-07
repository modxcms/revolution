<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modDashboard extends \MODX\Revolution\modDashboard
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'dashboard',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' => 
        array (
            'name' => NULL,
            'description' => NULL,
            'hide_trees' => 0,
            'customizable' => 1,
        ),
        'fieldMeta' => 
        array (
            'name' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'index' => 'index',
            ),
            'description' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
            ),
            'hide_trees' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'customizable' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 1,
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
            'hide_trees' => 
            array (
                'alias' => 'hide_trees',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'hide_trees' => 
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
            'Placements' => 
            array (
                'class' => 'MODX\\Revolution\\modDashboardWidgetPlacement',
                'local' => 'id',
                'foreign' => 'dashboard',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
        'aggregates' => 
        array (
            'UserGroups' => 
            array (
                'class' => 'MODX\\Revolution\\modUserGroup',
                'local' => 'id',
                'foreign' => 'dashboard',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
    );

}
