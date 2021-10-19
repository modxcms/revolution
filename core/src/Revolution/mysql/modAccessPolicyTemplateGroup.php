<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modAccessPolicyTemplateGroup extends \MODX\Revolution\modAccessPolicyTemplateGroup
{
    const GROUP_ADMINISTRATOR = 'Administrator';
    const GROUP_OBJECT = 'Object';
    const GROUP_RESOURCE = 'Resource';
    const GROUP_ELEMENT = 'Element';
    const GROUP_MEDIA_SOURCE = 'MediaSource';
    const GROUP_NAMESPACE = 'Namespace';
    const GROUP_CONTEXT = 'Context';

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'access_policy_template_groups',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'name' => '',
            'description' => NULL,
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
                'dbtype' => 'mediumtext',
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
