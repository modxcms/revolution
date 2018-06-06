<?php

namespace MODX\mysql;

use xPDO\xPDO;

class modEvent extends \MODX\modEvent
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'system_eventnames',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'name' => null,
                'service' => 0,
                'groupname' => '',
            ],
        'fieldMeta' =>
            [
                'name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '50',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'pk',
                    ],
                'service' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '4',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'groupname' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '20',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
            ],
        'indexes' =>
            [
                'PRIMARY' =>
                    [
                        'alias' => 'PRIMARY',
                        'primary' => true,
                        'unique' => true,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'name' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'aggregates' =>
            [
                'PluginEvents' =>
                    [
                        'class' => 'MODX\\modPluginEvent',
                        'local' => 'name',
                        'foreign' => 'event',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
    ];


    public static function listEvents(xPDO &$xpdo, $plugin, array $criteria = [], array $sort = ['id' => 'ASC'], $limit = 0, $offset = 0)
    {
        $c = $xpdo->newQuery('modEvent');
        $count = $xpdo->getCount('modEvent', $c);
        $c->select($xpdo->getSelectColumns('modEvent', 'modEvent'));
        $c->select([
            'IF(ISNULL(modPluginEvent.pluginid),0,1) AS enabled',
            'modPluginEvent.priority AS priority',
            'modPluginEvent.propertyset AS propertyset',
        ]);
        $c->leftJoin('modPluginEvent', 'modPluginEvent', '
            modPluginEvent.event = modEvent.name
            AND modPluginEvent.pluginid = ' . $plugin . '
        ');
        $c->where($criteria);
        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns('modEvent', 'modEvent', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }

        return [
            'count' => $count,
            'collection' => $xpdo->getCollection('modEvent', $c),
        ];
    }
}
