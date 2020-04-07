<?php
namespace MODX\Revolution\mysql;

class modTemplate extends \MODX\Revolution\modTemplate
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'site_templates',
        'extends' => 'MODX\\Revolution\\modElement',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'templatename' => '',
            'description' => 'Template',
            'editor_type' => 0,
            'category' => 0,
            'icon' => '',
            'template_type' => 0,
            'content' => '',
            'locked' => 0,
            'properties' => NULL,
            'static' => 0,
            'static_file' => '',
        ),
        'fieldMeta' => 
        array (
            'templatename' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '50',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'unique',
            ),
            'description' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'default' => 'Template',
            ),
            'editor_type' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'category' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'fk',
            ),
            'icon' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'template_type' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'content' => 
            array (
                'dbtype' => 'mediumtext',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'locked' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'attributes' => 'unsigned',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'properties' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'array',
                'null' => true,
            ),
            'static' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'attributes' => 'unsigned',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'static_file' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
        ),
        'indexes' => 
        array (
            'templatename' => 
            array (
                'alias' => 'templatename',
                'primary' => false,
                'unique' => true,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'templatename' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'category' => 
            array (
                'alias' => 'category',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'category' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'locked' => 
            array (
                'alias' => 'locked',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'locked' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'static' => 
            array (
                'alias' => 'static',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'static' => 
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
            'PropertySets' => 
            array (
                'class' => 'MODX\\Revolution\\modElementPropertySet',
                'local' => 'id',
                'foreign' => 'element',
                'owner' => 'local',
                'cardinality' => 'many',
                'criteria' => 
                array (
                    'foreign' => 
                    array (
                        'element_class' => 'MODX\\Revolution\\modTemplate',
                    ),
                ),
            ),
            'TemplateVarTemplates' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplateVarTemplate',
                'local' => 'id',
                'foreign' => 'templateid',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
        'aggregates' => 
        array (
            'Category' => 
            array (
                'class' => 'MODX\\Revolution\\modCategory',
                'local' => 'category',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'Resources' => 
            array (
                'class' => 'MODX\\Revolution\\modResource',
                'local' => 'id',
                'foreign' => 'template',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
    );

    public static function listTemplateVars(
        \MODX\Revolution\modTemplate &$template,
        array $sort = ['name' => 'ASC'],
        $limit = 0,
        $offset = 0,
        array $conditions = []
    ) {
        $result = ['collection' => [], 'total' => 0];
        $c = $template->xpdo->newQuery(\MODX\Revolution\modTemplateVar::class);
        $result['total'] = $template->xpdo->getCount(\MODX\Revolution\modTemplateVar::class, $c);
        $c->select($template->xpdo->getSelectColumns(\MODX\Revolution\modTemplateVar::class, 'modTemplateVar'));
        $c->leftJoin(\MODX\Revolution\modTemplateVarTemplate::class, 'modTemplateVarTemplate', [
            "modTemplateVarTemplate.tmplvarid = modTemplateVar.id",
            'modTemplateVarTemplate.templateid' => $template->get('id'),
        ]);
        $c->leftJoin(\MODX\Revolution\modCategory::class, 'Category');
        if (!empty($conditions)) {
            $c->where($conditions);
        }
        $c->select([
            "IF(ISNULL(modTemplateVarTemplate.tmplvarid),0,1) AS access",
            "IF(ISNULL(modTemplateVarTemplate.rank),0,modTemplateVarTemplate.rank) AS tv_rank",
            'category_name' => 'Category.category',
        ]);
        foreach ($sort as $sortKey => $sortDir) {
            $c->sortby($sortKey, $sortDir);
        }
        if ($limit > 0) {
            $c->limit($limit, $offset);
        }
        $result['collection'] = $template->xpdo->getCollection(\MODX\Revolution\modTemplateVar::class, $c);

        return $result;
    }
}
