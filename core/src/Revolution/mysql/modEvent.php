<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modEvent extends \MODX\Revolution\modEvent
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'system_eventnames',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
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
                'dbtype' => 'varchar',
                'precision' => '50',
                'phptype' => 'string',
                'null' => false,
                'index' => 'pk',
            ),
            'service' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '4',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'groupname' => 
            array (
                'dbtype' => 'varchar',
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
        $c->select($xpdo->getSelectColumns(\MODX\Revolution\modEvent::class, 'modEvent'));
        $c->select([
            'IF(ISNULL(modPluginEvent.pluginid),0,1) AS enabled',
            'modPluginEvent.priority AS priority',
            'modPluginEvent.propertyset AS propertyset',
        ]);
        $c->leftJoin(modPluginEvent::class, 'modPluginEvent', '
            modPluginEvent.event = modEvent.name
            AND modPluginEvent.pluginid = ' . $plugin . '
        ');
        $c->where($criteria);
        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns(\MODX\Revolution\modEvent::class, 'modEvent', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }

        return [
            'count' => $count,
            'collection' => $xpdo->getCollection(\MODX\Revolution\modEvent::class, $c),
        ];
    }
}
