<?php

namespace MODX\mysql;

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
                        'dbtype' => 'varchar',
                        'precision' => '50',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'pk',
                    ],
                'value' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'xtype' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '75',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'textfield',
                    ],
                'namespace' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '40',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'core',
                    ],
                'area' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'editedon' =>
                    [
                        'dbtype' => 'timestamp',
                        'phptype' => 'timestamp',
                        'null' => true,
                        'default' => null,
                        'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
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
            'name_trans' => 'Entry.value',
            'description_trans' => 'Description.value',
        ]);
        $c->leftJoin('modLexiconEntry', 'Entry', "CONCAT('setting_',modSystemSetting.{$xpdo->escape('key')}) = Entry.name");
        $c->leftJoin('modLexiconEntry', 'Description', "CONCAT('setting_',modSystemSetting.{$xpdo->escape('key')},'_desc') = Description.name");
        $c->where($criteria);

        $count = $xpdo->getCount('modSystemSetting', $c);
        $c->sortby($xpdo->getSelectColumns('modSystemSetting', 'modSystemSetting', '', ['area']), 'ASC');
        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns('modSystemSetting', 'modSystemSetting', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }
        $c->prepare();

        return [
            'count' => $count,
            'collection' => $xpdo->getCollection('modSystemSetting', $c),
        ];
    }
}
