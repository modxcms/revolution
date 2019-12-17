<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modFormCustomizationProfileUserGroup extends \MODX\Revolution\modFormCustomizationProfileUserGroup
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'fc_profiles_usergroups',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'usergroup' => 0,
            'profile' => 0,
        ),
        'fieldMeta' => 
        array (
            'usergroup' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'pk',
            ),
            'profile' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'pk',
            ),
        ),
        'indexes' => 
        array (
            'PRIMARY' => 
            array (
                'alias' => 'PRIMARY',
                'primary' => true,
                'unique' => true,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'usergroup' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'profile' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
        'aggregates' => 
        array (
            'UserGroup' => 
            array (
                'class' => 'MODX\\Revolution\\modUserGroup',
                'local' => 'usergroup',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'Profile' => 
            array (
                'class' => 'MODX\\Revolution\\modFormCustomizationProfile',
                'local' => 'profile',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
