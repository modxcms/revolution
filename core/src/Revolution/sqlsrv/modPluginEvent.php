<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modPluginEvent extends \MODX\Revolution\modPluginEvent
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'site_plugin_events',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' => 
        array (
            'pluginid' => 0,
            'event' => '',
            'priority' => 0,
            'propertyset' => 0,
        ),
        'fieldMeta' => 
        array (
            'pluginid' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'pk',
            ),
            'event' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'pk',
            ),
            'priority' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'propertyset' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
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
                    'pluginid' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'event' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'priority' => 
            array (
                'alias' => 'priority',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'priority' => 
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
            'Plugin' => 
            array (
                'class' => 'MODX\\Revolution\\modPlugin',
                'local' => 'pluginid',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'Event' => 
            array (
                'class' => 'MODX\\Revolution\\modEvent',
                'local' => 'event',
                'foreign' => 'name',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'PropertySet' => 
            array (
                'class' => 'MODX\\Revolution\\modPropertySet',
                'local' => 'propertyset',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
