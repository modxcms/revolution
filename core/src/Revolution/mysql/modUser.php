<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modUser extends \MODX\Revolution\modUser
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'users',
        'extends' => 'MODX\\Revolution\\modPrincipal',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'username' => '',
            'password' => '',
            'cachepwd' => '',
            'class_key' => 'MODX\\Revolution\\modUser',
            'active' => 1,
            'remote_key' => NULL,
            'remote_data' => NULL,
            'hash_class' => 'MODX\\Revolution\\Hashing\\modNative',
            'salt' => '',
            'primary_group' => 0,
            'session_stale' => NULL,
            'sudo' => 0,
            'createdon' => 0,
        ),
        'fieldMeta' => 
        array (
            'username' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'unique',
            ),
            'password' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'cachepwd' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'class_key' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => 'MODX\\Revolution\\modUser',
                'index' => 'index',
            ),
            'active' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'phptype' => 'boolean',
                'attributes' => 'unsigned',
                'null' => false,
                'default' => 1,
            ),
            'remote_key' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => true,
                'index' => 'index',
            ),
            'remote_data' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'json',
                'null' => true,
            ),
            'hash_class' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => 'MODX\\Revolution\\Hashing\\modNative',
            ),
            'salt' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'primary_group' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'phptype' => 'integer',
                'attributes' => 'unsigned',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'session_stale' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'array',
                'null' => true,
            ),
            'sudo' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'phptype' => 'boolean',
                'attributes' => 'unsigned',
                'null' => false,
                'default' => 0,
            ),
            'createdon' => 
            array (
                'dbtype' => 'int',
                'precision' => '20',
                'phptype' => 'timestamp',
                'null' => false,
                'default' => 0,
            ),
        ),
        'indexes' => 
        array (
            'username' => 
            array (
                'alias' => 'username',
                'primary' => false,
                'unique' => true,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'username' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'class_key' => 
            array (
                'alias' => 'class_key',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'class_key' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'remote_key' => 
            array (
                'alias' => 'remote_key',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'remote_key' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'primary_group' => 
            array (
                'alias' => 'primary_group',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'primary_group' => 
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
            'Profile' => 
            array (
                'class' => 'MODX\\Revolution\\modUserProfile',
                'local' => 'id',
                'foreign' => 'internalKey',
                'cardinality' => 'one',
                'owner' => 'local',
            ),
            'UserSettings' => 
            array (
                'class' => 'MODX\\Revolution\\modUserSetting',
                'local' => 'id',
                'foreign' => 'user',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'UserGroupMembers' => 
            array (
                'class' => 'MODX\\Revolution\\modUserGroupMember',
                'local' => 'id',
                'foreign' => 'member',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'ActiveUsers' => 
            array (
                'class' => 'MODX\\Revolution\\modActiveUser',
                'local' => 'id',
                'foreign' => 'internalKey',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'DashboardWidgets' => 
            array (
                'class' => 'MODX\\Revolution\\modDashboardWidgetPlacement',
                'local' => 'id',
                'foreign' => 'user',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
        'aggregates' => 
        array (
            'CreatedResources' => 
            array (
                'class' => 'MODX\\Revolution\\modResource',
                'local' => 'id',
                'foreign' => 'createdby',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'EditedResources' => 
            array (
                'class' => 'MODX\\Revolution\\modResource',
                'local' => 'id',
                'foreign' => 'editedby',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'DeletedResources' => 
            array (
                'class' => 'MODX\\Revolution\\modResource',
                'local' => 'id',
                'foreign' => 'deletedby',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'PublishedResources' => 
            array (
                'class' => 'MODX\\Revolution\\modResource',
                'local' => 'id',
                'foreign' => 'publishedby',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'SentMessages' => 
            array (
                'class' => 'MODX\\Revolution\\modUserMessage',
                'local' => 'id',
                'foreign' => 'sender',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'ReceivedMessages' => 
            array (
                'class' => 'MODX\\Revolution\\modUserMessage',
                'local' => 'id',
                'foreign' => 'recipient',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'PrimaryGroup' => 
            array (
                'class' => 'MODX\\Revolution\\modUserGroup',
                'local' => 'primary_group',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
