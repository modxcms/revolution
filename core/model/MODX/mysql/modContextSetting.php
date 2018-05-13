<?php

namespace MODX\mysql;

use xPDO\xPDO;

class modContextSetting extends \MODX\modContextSetting
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'context_setting',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'context_key' => null,
                'key' => null,
                'value' => null,
                'xtype' => 'textfield',
                'namespace' => 'core',
                'area' => '',
                'editedon' => null,
            ],
        'fieldMeta' =>
            [
                'context_key' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'pk',
                    ],
                'key' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '50',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'pk',
                    ],
                'value' =>
                    [
                        'dbtype' => 'mediumtext',
                        'phptype' => 'string',
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
                        'columns' =>
                            [
                                'context_key' =>
                                    [
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'key' =>
                                    [
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'aggregates' =>
            [
                'Context' =>
                    [
                        'class' => 'MODX\\modContext',
                        'key' => 'context_key',
                        'local' => 'context_key',
                        'foreign' => 'key',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'SystemSetting' =>
                    [
                        'class' => 'MODX\\modSystemSetting',
                        'key' => 'key',
                        'local' => 'key',
                        'foreign' => 'key',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];


    public static function listSettings(xPDO &$xpdo, array $criteria = [], array $sort = ['id' => 'ASC'], $limit = 0, $offset = 0)
    {
        /* build query */
        $c = $xpdo->newQuery('modContextSetting');
        $c->select([
            $xpdo->getSelectColumns('modContextSetting', 'modContextSetting'),
        ]);
        $c->select([
            'Entry.value AS name_trans',
            'Description.value AS description_trans',
        ]);
        $c->leftJoin('modLexiconEntry', 'Entry', "CONCAT('setting_',modContextSetting.{$xpdo->escape('key')}) = Entry.name");
        $c->leftJoin('modLexiconEntry', 'Description', "CONCAT('setting_',modContextSetting.{$xpdo->escape('key')},'_desc') = Description.name");
        $c->where($criteria);

        $count = $xpdo->getCount('modContextSetting', $c);
        $c->sortby($xpdo->getSelectColumns('modContextSetting', 'modContextSetting', '', ['area']), 'ASC');
        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns('modContextSetting', 'modContextSetting', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }

        return [
            'count' => $count,
            'collection' => $xpdo->getCollection('modContextSetting', $c),
        ];
    }
}
