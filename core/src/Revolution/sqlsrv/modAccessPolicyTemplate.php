<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modAccessPolicyTemplate extends \MODX\Revolution\modAccessPolicyTemplate
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'access_policy_templates',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' => 
        array (
            'template_group' => 0,
            'name' => '',
            'description' => NULL,
            'lexicon' => 'permissions',
        ),
        'fieldMeta' => 
        array (
            'template_group' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
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
            ),
            'lexicon' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => 'permissions',
            ),
        ),
        'composites' => 
        array (
            'Permissions' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessPermission',
                'local' => 'id',
                'foreign' => 'template',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
            'Policies' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessPolicy',
                'local' => 'id',
                'foreign' => 'template',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
        ),
        'aggregates' => 
        array (
            'TemplateGroup' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessPolicyTemplateGroup',
                'local' => 'template_group',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
        ),
    );

}
