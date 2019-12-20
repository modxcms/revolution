<?php
namespace MODX\Revolution\sqlsrv;

use PDO;
use xPDO\Om\xPDOObject;
use xPDO\xPDO;

class modFormCustomizationProfile extends \MODX\Revolution\modFormCustomizationProfile
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'fc_profiles',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
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
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'index',
            ),
            'description' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
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
        $objCollection = [];

        /* query for profiles */
        $c = $xpdo->newQuery(\MODX\Revolution\modFormCustomizationProfile::class);
        $c->select([
            $xpdo->getSelectColumns(\MODX\Revolution\modFormCustomizationProfile::class, 'modFormCustomizationProfile'),
        ]);
        $c->where($criteria, null, 2);// also log issue in remine to look at this usage of where()
        $count = $xpdo->getCount(\MODX\Revolution\modFormCustomizationProfile::class, $c);

        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns(\MODX\Revolution\modFormCustomizationProfile::class,
                'modFormCustomizationProfile', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }

        $rows = xPDOObject::_loadRows($xpdo, \MODX\Revolution\modFormCustomizationProfile::class, $c);
        $rowsArray = $rows->fetchAll(PDO::FETCH_ASSOC);
        $rows->closeCursor();
        foreach ($rowsArray as $row) {
            $objCollection[] = $xpdo->call(\MODX\Revolution\modFormCustomizationProfile::class, '_loadInstance',
                [&$xpdo, \MODX\Revolution\modFormCustomizationProfile::class, $c, $row]);
        }
        unset($row, $rowsArray);

        return [
            'count' => $count,
            'collection' => $objCollection,
        ];
    }

    public static function _loadInstance(& $xpdo, $className, $criteria, $row)
    {
        $sql = "SELECT gr.[name]
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
