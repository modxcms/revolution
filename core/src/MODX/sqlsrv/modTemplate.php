<?php

namespace MODX\sqlsrv;


class modTemplate extends \MODX\modTemplate
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'site_templates',
        'extends' => 'MODX\\modElement',
        'fields' =>
            [
                'templatename' => '',
                'description' => 'Template',
                'editor_type' => 0,
                'category' => 0,
                'icon' => '',
                'template_type' => 0,
                'content' => '',
                'locked' => 0,
                'properties' => null,
                'static' => 0,
                'static_file' => '',
            ],
        'fieldMeta' =>
            [
                'templatename' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '50',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'unique',
                    ],
                'description' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'Template',
                    ],
                'editor_type' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'category' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'fk',
                    ],
                'icon' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'template_type' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'content' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'locked' =>
                    [
                        'dbtype' => 'bit',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'properties' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'array',
                        'null' => true,
                    ],
                'static' =>
                    [
                        'dbtype' => 'bit',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'static_file' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
            ],
        'indexes' =>
            [
                'templatename' =>
                    [
                        'alias' => 'templatename',
                        'primary' => false,
                        'unique' => true,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'templatename' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'category' =>
                    [
                        'alias' => 'category',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'category' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'locked' =>
                    [
                        'alias' => 'locked',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'locked' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'static' =>
                    [
                        'alias' => 'static',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'static' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'composites' =>
            [
                'PropertySets' =>
                    [
                        'class' => 'MODX\\modElementPropertySet',
                        'local' => 'id',
                        'foreign' => 'element',
                        'owner' => 'local',
                        'cardinality' => 'many',
                        'criteria' =>
                            [
                                'foreign' =>
                                    [
                                        'element_class' => 'modTemplate',
                                    ],
                            ],
                    ],
                'TemplateVarTemplates' =>
                    [
                        'class' => 'MODX\\modTemplateVarTemplate',
                        'local' => 'id',
                        'foreign' => 'templateid',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
        'aggregates' =>
            [
                'Category' =>
                    [
                        'class' => 'MODX\\modCategory',
                        'local' => 'category',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Resources' =>
                    [
                        'class' => 'MODX\\modResource',
                        'local' => 'id',
                        'foreign' => 'template',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
    ];


    public static function listTemplateVars(\MODX\modTemplate &$template, array $sort = ['name' => 'ASC'], $limit = 0, $offset = 0, array $conditions = [])
    {
        $result = ['collection' => [], 'total' => 0];
        $c = $template->xpdo->newQuery('modTemplateVar');
        $result['total'] = $template->xpdo->getCount('modTemplateVar', $c);
        $c->select($template->xpdo->getSelectColumns('modTemplateVar', 'modTemplateVar'));
        $c->leftJoin('modTemplateVarTemplate', 'modTemplateVarTemplate', [
            "modTemplateVarTemplate.tmplvarid = modTemplateVar.id",
            'modTemplateVarTemplate.templateid' => $template->get('id'),
        ]);
        $c->leftJoin('modCategory', 'Category');
        if (!empty($conditions)) {
            $c->where($conditions);
        }
        $c->select([
            "CASE modTemplateVarTemplate.tmplvarid WHEN NULL THEN 0 ELSE 1 END AS access",
            "ISNULL(modTemplateVarTemplate.rank, 0) AS tv_rank",
            'category_name' => 'Category.category',
        ]);
        foreach ($sort as $sortKey => $sortDir) {
            $c->sortby($sortKey, $sortDir);
        }
        if ($limit > 0) $c->limit($limit, $offset);
        $result['collection'] = $template->xpdo->getCollection('modTemplateVar', $c);

        return $result;
    }
}
