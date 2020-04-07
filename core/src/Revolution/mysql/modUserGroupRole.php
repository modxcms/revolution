<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modUserGroupRole extends \MODX\Revolution\modUserGroupRole
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'user_group_roles',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'name' => NULL,
            'description' => NULL,
            'authority' => 9999,
        ),
        'fieldMeta' => 
        array (
            'name' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'index' => 'unique',
            ),
            'description' => 
            array (
                'dbtype' => 'mediumtext',
                'phptype' => 'string',
            ),
            'authority' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 9999,
                'index' => 'index',
            ),
        ),
        'indexes' => 
        array (
            'name' => 
            array (
                'alias' => 'name',
                'primary' => false,
                'unique' => true,
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
            'authority' => 
            array (
                'alias' => 'authority',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'authority' => 
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
            'UserGroupMembers' => 
            array (
                'class' => 'MODX\\Revolution\\modUserGroupMember',
                'local' => 'id',
                'foreign' => 'role',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
    );

}
