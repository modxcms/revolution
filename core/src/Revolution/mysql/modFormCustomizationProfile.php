<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modFormCustomizationProfile extends \MODX\Revolution\modFormCustomizationProfile
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'fc_profiles',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'name' => '',
            'description' => '',
            'active' => 0,
            'rank' => 0,
        ),
        'fieldMeta' => 
        array (
            'name' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'index',
            ),
            'description' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'active' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'rank' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
        ),
        'indexes' => 
        array (
            'name' => 
            array (
                'alias' => 'name',
                'primary' => false,
                'unique' => false,
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
            'rank' => 
            array (
                'alias' => 'rank',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'rank' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'active' => 
            array (
                'alias' => 'active',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'active' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
        'composites' => 
        array (
            'Sets' => 
            array (
                'class' => 'MODX\\Revolution\\modFormCustomizationSet',
                'local' => 'id',
                'foreign' => 'profile',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'UserGroups' => 
            array (
                'class' => 'MODX\\Revolution\\modFormCustomizationProfileUserGroup',
                'local' => 'id',
                'foreign' => 'profile',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
    );

    public static function listProfiles(
        xPDO &$xpdo,
        array $criteria = [],
        array $sort = ['id' => 'ASC'],
        $limit = 0,
        $offset = 0
    ) {
        /* query for profiles */
        $c = $xpdo->newQuery(\MODX\Revolution\modFormCustomizationProfile::class);
        $c->select([
            'modFormCustomizationProfile.*',
        ]);
        $c->select('
            (SELECT GROUP_CONCAT(UserGroup.name) FROM ' . $xpdo->getTableName(\MODX\Revolution\modUserGroup::class) . ' AS UserGroup
                INNER JOIN ' . $xpdo->getTableName(\MODX\Revolution\modFormCustomizationProfileUserGroup::class) . ' AS fcpug
                ON fcpug.usergroup = UserGroup.id
             WHERE fcpug.profile = modFormCustomizationProfile.id
            ) AS usergroups
        ');
        $c->where($criteria, null, 2);// also log issue in remine to look at this usage of where()
        $count = $xpdo->getCount(\MODX\Revolution\modFormCustomizationProfile::class, $c);

        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns(\MODX\Revolution\modFormCustomizationProfile::class, 'modFormCustomizationProfile', '',
                [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }

        return [
            'count' => $count,
            'collection' => $xpdo->getCollection(\MODX\Revolution\modFormCustomizationProfile::class, $c),
        ];
    }
}
