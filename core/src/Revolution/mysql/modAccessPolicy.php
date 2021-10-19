<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modAccessPolicy extends \MODX\Revolution\modAccessPolicy
{
    const POLICY_RESOURCE = 'Resource';
    const POLICY_ADMINISTRATOR = 'Administrator';
    const POLICY_LOAD_ONLY = 'Load Only';
    const POLICY_LOAD_LIST_VIEW = 'Load, List and View';
    const POLICY_OBJECT = 'Object';
    const POLICY_ELEMENT = 'Element';
    const POLICY_CONTENT_EDITOR = 'Content Editor';
    const POLICY_MEDIA_SOURCE_ADMIN = 'Media Source Admin';
    const POLICY_MEDIA_SOURCE_USER = 'Media Source User';
    const POLICY_DEVELOPER = 'Developer';
    const POLICY_CONTEXT = 'Context';
    const POLICY_HIDDEN_NAMESPACE = 'Hidden Namespace';

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'access_policies',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'name' => NULL,
            'description' => NULL,
            'parent' => 0,
            'template' => 0,
            'class' => '',
            'data' => '{}',
            'lexicon' => 'permissions',
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
            'parent' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'template' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'class' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'index',
            ),
            'data' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'json',
                'default' => '{}',
            ),
            'lexicon' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => 'permissions',
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
            'parent' => 
            array (
                'alias' => 'parent',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'parent' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'class' => 
            array (
                'alias' => 'class',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'class' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'template' => 
            array (
                'alias' => 'template',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'template' => 
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
            'Children' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessPolicy',
                'local' => 'id',
                'foreign' => 'parent',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
        ),
        'aggregates' => 
        array (
            'Parent' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessPolicy',
                'local' => 'parent',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
            'Template' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessPolicyTemplate',
                'local' => 'template',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
        ),
    );

}
