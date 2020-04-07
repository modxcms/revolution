<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modEvent extends \MODX\Revolution\modEvent
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'system_eventnames',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' => 
        array (
            'name' => NULL,
            'service' => 0,
            'groupname' => '',
        ),
        'fieldMeta' => 
        array (
            'name' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '50',
                'phptype' => 'string',
                'null' => false,
                'index' => 'pk',
            ),
            'service' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '4',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'groupname' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '20',
                'phptype' => 'string',
                'null' => false,
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
        'aggregates' => 
        array (
            'PluginEvents' => 
            array (
                'class' => 'MODX\\Revolution\\modPluginEvent',
                'local' => 'name',
                'foreign' => 'event',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
    );

    public static function listEvents(
        xPDO &$xpdo,
        $plugin,
        array $criteria = [],
        array $sort = ['id' => 'ASC'],
        $limit = 0,
        $offset = 0
    ) {
        $c = $xpdo->newQuery(\MODX\Revolution\modEvent::class);
        $count = $xpdo->getCount(\MODX\Revolution\modEvent::class, $c);
        $c->leftJoin(\MODX\Revolution\modPluginEvent::class, 'modPluginEvent', '
            modPluginEvent.event = modEvent.name
            AND modPluginEvent.pluginid = ' . $plugin . '
        ');
        $c->select([
            'modEvent.*',
            'CASE WHEN modPluginEvent.pluginid IS NULL THEN 0 ELSE 1 END AS enabled',
            'modPluginEvent.priority AS priority',
            'modPluginEvent.propertyset AS propertyset',
        ]);
        $c->where($criteria);
        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns(\MODX\Revolution\modEvent::class, 'modEvent', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }
        $c->prepare();

        return [
            'count' => $count,
            'collection' => $xpdo->getCollection(\MODX\Revolution\modEvent::class, $c),
        ];
    }
}
