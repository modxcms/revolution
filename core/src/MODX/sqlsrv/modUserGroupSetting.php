<?php

namespace MODX\sqlsrv;

use xPDO\xPDO;

class modUserGroupSetting extends \MODX\modUserGroupSetting
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'user_group_settings',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'group' => '0',
                'key' => '',
                'value' => null,
                'xtype' => 'textfield',
                'namespace' => 'core',
                'area' => '',
                'editedon' => null,
            ],
        'fieldMeta' =>
            [
                'group' =>
                    [
                        'dbtype' => 'integer',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => '0',
                        'index' => 'pk',
                    ],
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
                                'group' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
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
                'UserGroup' =>
                    [
                        'class' => 'MODX\\modUserGroup',
                        'local' => 'group',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
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
        $c = $xpdo->newQuery('modUserGroupSetting');
        $c->select([
            $xpdo->getSelectColumns('modUserGroupSetting', 'modUserGroupSetting'),
            'Entry.value AS name_trans',
            'Description.value AS description_trans',
        ]);
        $c->leftJoin('modLexiconEntry', 'Entry', "'setting_' + modUserGroupSetting.[key] = Entry.name");
        $c->leftJoin('modLexiconEntry', 'Description', "'setting_' + modUserGroupSetting.[key] + '_desc' = Description.name");
        $c->where($criteria);
        $count = $xpdo->getCount('modUserGroupSetting', $c);
        $c->sortby($xpdo->getSelectColumns('modUserGroupSetting', 'modUserGroupSetting', '', ['area']), 'ASC');
        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns('modUserGroupSetting', 'modUserGroupSetting', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }

        return [
            'count' => $count,
            'collection' => $xpdo->getCollection('modUserGroupSetting', $c),
        ];
    }
}
