<?php

namespace MODX\sqlsrv;

use xPDO\xPDO;
use xPDO\Om\xPDOObject;
use PDO;

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
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'description' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
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
        $objCollection = [];

        /* query for profiles */
        $c = $xpdo->newQuery('modFormCustomizationProfile');
        $c->select([
            $xpdo->getSelectColumns('modFormCustomizationProfile', 'modFormCustomizationProfile'),
        ]);
        $c->where($criteria, null, 2);// also log issue in remine to look at this usage of where()
        $count = $xpdo->getCount('modFormCustomizationProfile', $c);

        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns('modFormCustomizationProfile', 'modFormCustomizationProfile', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }

        $rows = xPDOObject:: _loadRows($xpdo, 'modFormCustomizationProfile', $c);
        $rowsArray = $rows->fetchAll(PDO::FETCH_ASSOC);
        $rows->closeCursor();
        foreach ($rowsArray as $row) {
            $objCollection[] = $xpdo->call('modFormCustomizationProfile', '_loadInstance', [&$xpdo, 'modFormCustomizationProfile', $c, $row]);
        }
        unset($row, $rowsArray);

        return [
            'count' => $count,
            'collection' => $objCollection,
        ];
    }


    public static function _loadInstance(& $xpdo, $className, $criteria, $row)
    {
        $sql = 'SELECT gr.[name]' . "
             FROM {$xpdo->config['table_prefix']}membergroup_names AS gr,
              {$xpdo->config['table_prefix']}fc_profiles_usergroups AS pu,
              {$xpdo->config['table_prefix']}fc_profiles AS pr
             WHERE gr.id = pu.usergroup
               AND pu.profile = pr.id
               AND pr.id = {$row['id']}
               ORDER BY gr.name";
        $groupNamesStatement = $xpdo->query($sql);
        $groupNamesArray = $groupNamesStatement->fetchAll(PDO::FETCH_COLUMN, 0);
        $row['usergroups'] = implode(', ', $groupNamesArray);

        return parent:: _loadInstance($xpdo, $className, $criteria, $row);
    }
}
