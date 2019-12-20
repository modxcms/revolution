<?php
namespace MODX\Revolution\sqlsrv;

class modResource extends \MODX\Revolution\modResource
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'site_content',
        'extends' => 'MODX\\Revolution\\modAccessibleSimpleObject',
        'inherit' => 'single',
        'fields' => 
        array (
            'type' => 'document',
            'contentType' => 'text/html',
            'pagetitle' => '',
            'longtitle' => '',
            'description' => '',
            'alias' => '',
            'link_attributes' => '',
            'published' => 0,
            'pub_date' => 0,
            'unpub_date' => 0,
            'parent' => 0,
            'isfolder' => 0,
            'introtext' => NULL,
            'content' => NULL,
            'richtext' => 1,
            'template' => 0,
            'menuindex' => 0,
            'searchable' => 1,
            'cacheable' => 1,
            'createdby' => 0,
            'createdon' => 0,
            'editedby' => 0,
            'editedon' => 0,
            'deleted' => 0,
            'deletedon' => 0,
            'deletedby' => 0,
            'publishedon' => 0,
            'publishedby' => 0,
            'menutitle' => '',
            'donthit' => 0,
            'privateweb' => 0,
            'privatemgr' => 0,
            'content_dispo' => 0,
            'hidemenu' => 0,
            'class_key' => 'MODX\\Revolution\\modDocument',
            'context_key' => 'web',
            'content_type' => 1,
            'uri' => '',
            'uri_override' => 0,
            'hide_children_in_tree' => 0,
            'show_in_tree' => 1,
            'properties' => NULL,
            'alias_visible' => 1,
        ),
        'fieldMeta' => 
        array (
            'type' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '20',
                'phptype' => 'string',
                'null' => false,
                'default' => 'document',
            ),
            'contentType' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '50',
                'phptype' => 'string',
                'null' => false,
                'default' => 'text/html',
            ),
            'pagetitle' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'fulltext',
                'indexgrp' => 'content_ft_idx',
            ),
            'longtitle' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'fulltext',
                'indexgrp' => 'content_ft_idx',
            ),
            'description' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'fulltext',
                'indexgrp' => 'content_ft_idx',
            ),
            'alias' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => true,
                'default' => '',
                'index' => 'index',
            ),
            'link_attributes' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'published' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'pub_date' => 
            array (
                'dbtype' => 'bigint',
                'phptype' => 'timestamp',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'unpub_date' => 
            array (
                'dbtype' => 'bigint',
                'phptype' => 'timestamp',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'parent' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'isfolder' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'introtext' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
                'index' => 'fulltext',
                'indexgrp' => 'content_ft_idx',
            ),
            'content' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
                'index' => 'fulltext',
                'indexgrp' => 'content_ft_idx',
            ),
            'richtext' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 1,
            ),
            'template' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'menuindex' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'searchable' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 1,
                'index' => 'index',
            ),
            'cacheable' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 1,
                'index' => 'index',
            ),
            'createdby' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'createdon' => 
            array (
                'dbtype' => 'bigint',
                'phptype' => 'timestamp',
                'null' => false,
                'default' => 0,
            ),
            'editedby' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'editedon' => 
            array (
                'dbtype' => 'bigint',
                'phptype' => 'timestamp',
                'null' => false,
                'default' => 0,
            ),
            'deleted' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
            ),
            'deletedon' => 
            array (
                'dbtype' => 'bigint',
                'phptype' => 'timestamp',
                'null' => false,
                'default' => 0,
            ),
            'deletedby' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'publishedon' => 
            array (
                'dbtype' => 'bigint',
                'phptype' => 'timestamp',
                'null' => false,
                'default' => 0,
            ),
            'publishedby' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'menutitle' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'donthit' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
            ),
            'privateweb' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
            ),
            'privatemgr' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
            ),
            'content_dispo' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'hidemenu' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'class_key' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => 'MODX\\Revolution\\modDocument',
                'index' => 'index',
            ),
            'context_key' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => 'web',
                'index' => 'index',
            ),
            'content_type' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 1,
            ),
            'uri' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => '1000',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'index',
            ),
            'uri_override' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'hide_children_in_tree' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'show_in_tree' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 1,
                'index' => 'index',
            ),
            'properties' => 
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'json',
                'null' => true,
            ),
            'alias_visible' => 
            array (
                'dbtype' => 'bit',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 1,
            ),
        ),
        'indexes' => 
        array (
            'alias' => 
            array (
                'alias' => 'alias',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'alias' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => true,
                    ),
                ),
            ),
            'published' => 
            array (
                'alias' => 'published',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'published' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'pub_date' => 
            array (
                'alias' => 'pub_date',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'pub_date' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'unpub_date' => 
            array (
                'alias' => 'unpub_date',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'unpub_date' => 
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
            'isfolder' => 
            array (
                'alias' => 'isfolder',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'isfolder' => 
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
            'menuindex' => 
            array (
                'alias' => 'menuindex',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'menuindex' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'searchable' => 
            array (
                'alias' => 'searchable',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'searchable' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'cacheable' => 
            array (
                'alias' => 'cacheable',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'cacheable' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'hidemenu' => 
            array (
                'alias' => 'hidemenu',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'hidemenu' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'class_key' => 
            array (
                'alias' => 'class_key',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'class_key' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'context_key' => 
            array (
                'alias' => 'context_key',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'context_key' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'uri' => 
            array (
                'alias' => 'uri',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'uri' => 
                    array (
                        'length' => '1000',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'uri_override' => 
            array (
                'alias' => 'uri_override',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'uri_override' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'hide_children_in_tree' => 
            array (
                'alias' => 'hide_children_in_tree',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'hide_children_in_tree' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'show_in_tree' => 
            array (
                'alias' => 'show_in_tree',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'show_in_tree' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'content_ft_idx' => 
            array (
                'alias' => 'content_ft_idx',
                'primary' => false,
                'unique' => false,
                'type' => 'FULLTEXT',
                'columns' => 
                array (
                    'pagetitle' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'longtitle' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'description' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'introtext' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => true,
                    ),
                    'content' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => true,
                    ),
                ),
            ),
        ),
        'composites' => 
        array (
            'Children' => 
            array (
                'class' => 'MODX\\Revolution\\modResource',
                'local' => 'id',
                'foreign' => 'parent',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'TemplateVarResources' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplateVarResource',
                'local' => 'id',
                'foreign' => 'contentid',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'ResourceGroupResources' => 
            array (
                'class' => 'MODX\\Revolution\\modResourceGroupResource',
                'local' => 'id',
                'foreign' => 'document',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'Acls' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessResource',
                'local' => 'id',
                'foreign' => 'target',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
            'ContextResources' => 
            array (
                'class' => 'MODX\\Revolution\\modContextResource',
                'local' => 'id',
                'foreign' => 'resource',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
        'aggregates' => 
        array (
            'Parent' => 
            array (
                'class' => 'MODX\\Revolution\\modResource',
                'local' => 'parent',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'CreatedBy' => 
            array (
                'class' => 'MODX\\Revolution\\modUser',
                'local' => 'createdby',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'EditedBy' => 
            array (
                'class' => 'MODX\\Revolution\\modUser',
                'local' => 'editedby',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'DeletedBy' => 
            array (
                'class' => 'MODX\\Revolution\\modUser',
                'local' => 'deletedby',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'PublishedBy' => 
            array (
                'class' => 'MODX\\Revolution\\modUser',
                'local' => 'publishedby',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'Template' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplate',
                'local' => 'template',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'TemplateVars' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplateVar',
                'local' => 'id:template',
                'foreign' => 'contentid:templateid',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'TemplateVarTemplates' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplateVarTemplate',
                'local' => 'template',
                'foreign' => 'templateid',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'ContentType' => 
            array (
                'class' => 'MODX\\Revolution\\modContentType',
                'local' => 'content_type',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
            'Context' => 
            array (
                'class' => 'MODX\\Revolution\\modContext',
                'local' => 'context_key',
                'foreign' => 'key',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
        ),
    );

    public static function listGroups(
        \MODX\Revolution\modResource &$resource,
        array $sort = ['id' => 'ASC'],
        $limit = 0,
        $offset = 0
    ) {
        $result = ['collection' => [], 'total' => 0];
        $c = $resource->xpdo->newQuery(\MODX\Revolution\modResourceGroup::class);
        $c->leftJoin(\MODX\Revolution\modResourceGroupResource::class, 'ResourceGroupResource', [
            "ResourceGroupResource.document_group = modResourceGroup.id",
            'ResourceGroupResource.document' => $resource->get('id'),
        ]);
        $result['total'] = $resource->xpdo->getCount(\MODX\Revolution\modResourceGroup::class, $c);
        $c->select($resource->xpdo->getSelectColumns(\MODX\Revolution\modResourceGroup::class, 'modResourceGroup'));
        $c->select(["ISNULL(ResourceGroupResource.document,0) AS access"]);
        foreach ($sort as $sortKey => $sortDir) {
            $c->sortby($resource->xpdo->escape('modResourceGroup') . '.' . $resource->xpdo->escape($sortKey), $sortDir);
        }
        if ($limit > 0) {
            $c->limit($limit, $offset);
        }
        $result['collection'] = $resource->xpdo->getCollection(\MODX\Revolution\modResourceGroup::class, $c);

        return $result;
    }

    public static function getTemplateVarCollection(\MODX\Revolution\modResource &$resource)
    {
        $c = $resource->xpdo->newQuery(\MODX\Revolution\modTemplateVar::class);
        $c->query['distinct'] = 'DISTINCT';
        $c->select($resource->xpdo->getSelectColumns(\MODX\Revolution\modTemplateVar::class, 'modTemplateVar'));
        if ($resource->isNew()) {
            $c->select([
                "modTemplateVar.default_text AS value",
                "0 AS resourceId",
            ]);
        } else {
            $c->select([
                "ISNULL(tvc.value,modTemplateVar.default_text) AS value",
                "{$resource->get('id')} AS resourceId",
            ]);
        }
        $c->select($resource->xpdo->getSelectColumns(\MODX\Revolution\modTemplateVarTemplate::class, 'tvtpl', '',
            ['rank']));
        $c->innerJoin(\MODX\Revolution\modTemplateVarTemplate::class, 'tvtpl', [
            'tvtpl.tmplvarid = modTemplateVar.id',
            'tvtpl.templateid' => $resource->get('template'),
        ]);
        if (!$resource->isNew()) {
            $c->leftJoin(\MODX\Revolution\modTemplateVarResource::class, 'tvc', [
                'tvc.tmplvarid = modTemplateVar.id',
                'tvc.contentid' => $resource->get('id'),
            ]);
        }
        $c->sortby('tvtpl.rank,modTemplateVar.rank');

        return $resource->xpdo->getCollection(\MODX\Revolution\modTemplateVar::class, $c);
    }
}
