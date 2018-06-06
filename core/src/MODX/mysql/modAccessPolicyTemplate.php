<?php

namespace MODX\mysql;


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
                        'precision' => '10',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
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
                'lexicon' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
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
