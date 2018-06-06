<?php

namespace MODX\mysql;


class modAccessPolicyTemplateGroup extends \MODX\modAccessPolicyTemplateGroup
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'access_policy_template_groups',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'name' => '',
                'description' => null,
            ],
        'fieldMeta' =>
            [
                'name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'description' =>
                    [
                        'dbtype' => 'mediumtext',
                        'phptype' => 'string',
                    ],
            ],
        'composites' =>
            [
                'Templates' =>
                    [
                        'class' => 'MODX\\modAccessPolicyTemplate',
                        'local' => 'id',
                        'foreign' => 'template_group',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
            ],
    ];
}
