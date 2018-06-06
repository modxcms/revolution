<?php

namespace MODX\sqlsrv;

use xPDO\xPDO;

class modUserSetting extends \MODX\modUserSetting
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'user_settings',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'user' => 0,
                'key' => '',
                'value' => null,
                'xtype' => 'textfield',
                'namespace' => 'core',
                'area' => '',
                'editedon' => null,
            ],
        'fieldMeta' =>
            [
                'user' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
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
                                'user' =>
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
                'User' =>
                    [
                        'class' => 'MODX\\modUser',
                        'local' => 'user',
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
        $c = $xpdo->newQuery('modUserSetting');
        $c->select([
            $xpdo->getSelectColumns('modUserSetting', 'modUserSetting'),
            'Entry.value AS name_trans',
            'Description.value AS description_trans',
        ]);
        $c->leftJoin('modLexiconEntry', 'Entry', "'setting_' + modUserSetting.[key] = Entry.name");
        $c->leftJoin('modLexiconEntry', 'Description', "'setting_' + modUserSetting.[key] + '_desc' = Description.name");
        $c->where($criteria);
        $count = $xpdo->getCount('modUserSetting', $c);
        $c->sortby($xpdo->getSelectColumns('modUserSetting', 'modUserSetting', '', ['area']), 'ASC');
        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns('modUserSetting', 'modUserSetting', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }

        return [
            'count' => $count,
            'collection' => $xpdo->getCollection('modUserSetting', $c),
        ];
    }
}
