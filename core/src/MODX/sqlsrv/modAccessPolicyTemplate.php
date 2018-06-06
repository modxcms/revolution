<?php

namespace MODX\sqlsrv;


class modAccessPolicyTemplate extends \MODX\modAccessPolicyTemplate
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'access_policy_templates',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' =>
            [
                'template_group' => 0,
                'name' => '',
                'description' => null,
                'lexicon' => 'permissions',
            ],
        'fieldMeta' =>
            [
                'template_group' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
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
                'lexicon' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'permissions',
                    ],
            ],
        'composites' =>
            [
                'Permissions' =>
                    [
                        'class' => 'MODX\\modAccessPermission',
                        'local' => 'id',
                        'foreign' => 'template',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
                'Policies' =>
                    [
                        'class' => 'MODX\\modAccessPolicy',
                        'local' => 'id',
                        'foreign' => 'template',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
            ],
        'aggregates' =>
            [
                'TemplateGroup' =>
                    [
                        'class' => 'MODX\\modAccessPolicyTemplateGroup',
                        'local' => 'template_group',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
