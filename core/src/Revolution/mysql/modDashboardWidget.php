<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modDashboardWidget extends \MODX\Revolution\modDashboardWidget
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'dashboard_widget',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'name' => '',
            'description' => NULL,
            'type' => NULL,
            'content' => NULL,
            'properties' => NULL,
            'namespace' => '',
            'lexicon' => 'core:dashboards',
            'size' => 'half',
            'permission' => '',
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
            ),
            'type' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'index' => 'index',
            ),
            'content' => 
            array (
                'dbtype' => 'mediumtext',
                'phptype' => 'string',
            ),
            'properties' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'json',
                'null' => true,
            ),
            'namespace' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'index',
            ),
            'lexicon' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'default' => 'core:dashboards',
                'index' => 'index',
            ),
            'size' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => 'half',
            ),
            'permission' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
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
            'type' => 
            array (
                'alias' => 'type',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'type' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
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
            'lexicon' => 
            array (
                'alias' => 'lexicon',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'lexicon' => 
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
                'foreign' => 'widget',
                'cardinality' => 'many',
                'owner' => 'local',
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
