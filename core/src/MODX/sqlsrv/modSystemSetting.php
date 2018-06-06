<?php

namespace MODX\sqlsrv;

use xPDO\xPDO;

class modSystemSetting extends \MODX\modSystemSetting
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'system_settings',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'key' => '',
                'value' => '',
                'xtype' => 'textfield',
                'namespace' => 'core',
                'area' => '',
                'editedon' => null,
            ],
        'fieldMeta' =>
            [
                'key' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '50',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'pk',
                    ],
                'value' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'xtype' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '75',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'textfield',
                    ],
                'namespace' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '40',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'core',
                    ],
                'area' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'editedon' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'timestamp',
                        'null' => true,
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
                                'key' =>
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
                'ContextSetting' =>
                    [
                        'class' => 'MODX\\modContextSetting',
                        'local' => 'key',
                        'foreign' => 'key',
                        'cardinality' => 'one',
                        'owner' => 'local',
                    ],
                'Namespace' =>
                    [
                        'class' => 'MODX\\modNamespace',
                        'local' => 'namespace',
                        'foreign' => 'name',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];


    public static function listSettings(xPDO &$xpdo, array $criteria = [], array $sort = ['id' => 'ASC'], $limit = 0, $offset = 0)
    {
        /* build query */
        $c = $xpdo->newQuery('modSystemSetting');
        $c->select([
            $xpdo->getSelectColumns('modSystemSetting', 'modSystemSetting'),
        ]);
        $c->select([
            'Entry.value AS name_trans',
            'Description.value AS description_trans',
        ]);
        $c->leftJoin('modLexiconEntry', 'Entry', "'setting_' + modSystemSetting.{$xpdo->escape('key')} = Entry.name");
        $c->leftJoin('modLexiconEntry', 'Description', "'setting_' + modSystemSetting.{$xpdo->escape('key')} + '_desc' = Description.name");
        $c->where($criteria);

        $count = $xpdo->getCount('modSystemSetting', $c);
        $c->sortby($xpdo->getSelectColumns('modSystemSetting', 'modSystemSetting', '', ['area']), 'ASC');
        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns('modSystemSetting', 'modSystemSetting', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }

        return [
            'count' => $count,
            'collection' => $xpdo->getCollection('modSystemSetting', $c),
        ];
    }
}
