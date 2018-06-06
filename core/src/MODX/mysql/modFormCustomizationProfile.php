<?php

namespace MODX\mysql;

use xPDO\xPDO;

class modFormCustomizationProfile extends \MODX\modFormCustomizationProfile
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'fc_profiles',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'name' => '',
                'description' => '',
                'active' => 0,
                'rank' => 0,
            ],
        'fieldMeta' =>
            [
                'name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'description' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'active' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'rank' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '11',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
            ],
        'indexes' =>
            [
                'name' =>
                    [
                        'alias' => 'name',
                        'primary' => false,
                        'unique' => false,
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
                'rank' =>
                    [
                        'alias' => 'rank',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'rank' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'active' =>
                    [
                        'alias' => 'active',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'active' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'composites' =>
            [
                'Sets' =>
                    [
                        'class' => 'MODX\\modFormCustomizationSet',
                        'local' => 'id',
                        'foreign' => 'profile',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'UserGroups' =>
                    [
                        'class' => 'MODX\\modFormCustomizationProfileUserGroup',
                        'local' => 'id',
                        'foreign' => 'profile',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
    ];


    public static function listProfiles(xPDO &$xpdo, array $criteria = [], array $sort = ['id' => 'ASC'], $limit = 0, $offset = 0)
    {
        /* query for profiles */
        $c = $xpdo->newQuery('modFormCustomizationProfile');
        $c->select([
            'modFormCustomizationProfile.*',
        ]);
        $c->select('
            (SELECT GROUP_CONCAT(UserGroup.name) FROM ' . $xpdo->getTableName('modUserGroup') . ' AS UserGroup
                INNER JOIN ' . $xpdo->getTableName('modFormCustomizationProfileUserGroup') . ' AS fcpug
                ON fcpug.usergroup = UserGroup.id
             WHERE fcpug.profile = modFormCustomizationProfile.id
            ) AS usergroups
        ');
        $c->where($criteria, null, 2);// also log issue in remine to look at this usage of where()
        $count = $xpdo->getCount('modFormCustomizationProfile', $c);

        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns('modFormCustomizationProfile', 'modFormCustomizationProfile', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }

        return [
            'count' => $count,
            'collection' => $xpdo->getCollection('modFormCustomizationProfile', $c),
        ];
    }
}
