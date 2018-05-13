<?php

namespace MODX\sqlsrv;


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
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'description' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
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
