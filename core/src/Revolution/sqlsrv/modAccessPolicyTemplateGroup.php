<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modAccessPolicyTemplateGroup extends \MODX\Revolution\modAccessPolicyTemplateGroup
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'access_policy_template_groups',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' => 
        array (
            'name' => '',
            'description' => NULL,
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
            ),
        ),
        'composites' => 
        array (
            'Templates' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessPolicyTemplate',
                'local' => 'id',
                'foreign' => 'template_group',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
        ),
    );

}
