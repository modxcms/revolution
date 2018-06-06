<?php

namespace MODX\mysql;


class modResource extends \MODX\modResource
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'site_content',
        'extends' => 'MODX\\modAccessibleSimpleObject',
        'inherit' => 'single',
        'fields' =>
            [
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
                'introtext' => null,
                'content' => null,
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
                'class_key' => 'modDocument',
                'context_key' => 'web',
                'content_type' => 1,
                'uri' => null,
                'uri_override' => 0,
                'hide_children_in_tree' => 0,
                'show_in_tree' => 1,
                'properties' => null,
            ],
        'fieldMeta' =>
            [
                'type' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '20',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'document',
                    ],
                'contentType' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '50',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'text/html',
                    ],
                'pagetitle' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'fulltext',
                        'indexgrp' => 'content_ft_idx',
                    ],
                'longtitle' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'fulltext',
                        'indexgrp' => 'content_ft_idx',
                    ],
                'description' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'fulltext',
                        'indexgrp' => 'content_ft_idx',
                    ],
                'alias' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => true,
                        'default' => '',
                        'index' => 'index',
                    ],
                'link_attributes' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'published' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'pub_date' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '20',
                        'phptype' => 'timestamp',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'unpub_date' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '20',
                        'phptype' => 'timestamp',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'parent' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'isfolder' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'introtext' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'string',
                        'index' => 'fulltext',
                        'indexgrp' => 'content_ft_idx',
                    ],
                'content' =>
                    [
                        'dbtype' => 'mediumtext',
                        'phptype' => 'string',
                        'index' => 'fulltext',
                        'indexgrp' => 'content_ft_idx',
                    ],
                'richtext' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 1,
                    ],
                'template' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'menuindex' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'searchable' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 1,
                        'index' => 'index',
                    ],
                'cacheable' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 1,
                        'index' => 'index',
                    ],
                'createdby' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'createdon' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '20',
                        'phptype' => 'timestamp',
                        'null' => false,
                        'default' => 0,
                    ],
                'editedby' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'editedon' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '20',
                        'phptype' => 'timestamp',
                        'null' => false,
                        'default' => 0,
                    ],
                'deleted' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                    ],
                'deletedon' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '20',
                        'phptype' => 'timestamp',
                        'null' => false,
                        'default' => 0,
                    ],
                'deletedby' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'publishedon' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '20',
                        'phptype' => 'timestamp',
                        'null' => false,
                        'default' => 0,
                    ],
                'publishedby' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'menutitle' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'donthit' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                    ],
                'privateweb' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                    ],
                'privatemgr' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                    ],
                'content_dispo' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                    ],
                'hidemenu' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'class_key' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'modDocument',
                        'index' => 'index',
                    ],
                'context_key' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'web',
                        'index' => 'index',
                    ],
                'content_type' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '11',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 1,
                    ],
                'uri' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'string',
                        'null' => true,
                        'index' => 'index',
                    ],
                'uri_override' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'hide_children_in_tree' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'show_in_tree' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 1,
                        'index' => 'index',
                    ],
                'properties' =>
                    [
                        'dbtype' => 'mediumtext',
                        'phptype' => 'json',
                        'null' => true,
                    ],
            ],
        'indexes' =>
            [
                'alias' =>
                    [
                        'alias' => 'alias',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'alias' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => true,
                                    ],
                            ],
                    ],
                'published' =>
                    [
                        'alias' => 'published',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'published' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'pub_date' =>
                    [
                        'alias' => 'pub_date',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'pub_date' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'unpub_date' =>
                    [
                        'alias' => 'unpub_date',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'unpub_date' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'parent' =>
                    [
                        'alias' => 'parent',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'parent' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'isfolder' =>
                    [
                        'alias' => 'isfolder',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'isfolder' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'template' =>
                    [
                        'alias' => 'template',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'template' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'menuindex' =>
                    [
                        'alias' => 'menuindex',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'menuindex' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'searchable' =>
                    [
                        'alias' => 'searchable',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'searchable' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'cacheable' =>
                    [
                        'alias' => 'cacheable',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'cacheable' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'hidemenu' =>
                    [
                        'alias' => 'hidemenu',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'hidemenu' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'class_key' =>
                    [
                        'alias' => 'class_key',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'class_key' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'context_key' =>
                    [
                        'alias' => 'context_key',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'context_key' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'uri' =>
                    [
                        'alias' => 'uri',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'uri' =>
                                    [
                                        'length' => '191',
                                        'collation' => 'A',
                                        'null' => true,
                                    ],
                            ],
                    ],
                'uri_override' =>
                    [
                        'alias' => 'uri_override',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'uri_override' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'hide_children_in_tree' =>
                    [
                        'alias' => 'hide_children_in_tree',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'hide_children_in_tree' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'show_in_tree' =>
                    [
                        'alias' => 'show_in_tree',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'show_in_tree' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'content_ft_idx' =>
                    [
                        'alias' => 'content_ft_idx',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'FULLTEXT',
                        'columns' =>
                            [
                                'pagetitle' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'longtitle' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'description' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'introtext' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => true,
                                    ],
                                'content' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => true,
                                    ],
                            ],
                    ],
                'cache_refresh_idx' =>
                    [
                        'alias' => 'cache_refresh_index',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'parent' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'menuindex' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'id' =>
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
                'Children' =>
                    [
                        'class' => 'MODX\\modResource',
                        'local' => 'id',
                        'foreign' => 'parent',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'TemplateVarResources' =>
                    [
                        'class' => 'MODX\\modTemplateVarResource',
                        'local' => 'id',
                        'foreign' => 'contentid',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'ResourceGroupResources' =>
                    [
                        'class' => 'MODX\\modResourceGroupResource',
                        'local' => 'id',
                        'foreign' => 'document',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'Acls' =>
                    [
                        'class' => 'MODX\\modAccessResource',
                        'local' => 'id',
                        'foreign' => 'target',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
                'ContextResources' =>
                    [
                        'class' => 'MODX\\modContextResource',
                        'local' => 'id',
                        'foreign' => 'resource',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
        'aggregates' =>
            [
                'Parent' =>
                    [
                        'class' => 'MODX\\modResource',
                        'local' => 'parent',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'CreatedBy' =>
                    [
                        'class' => 'MODX\\modUser',
                        'local' => 'createdby',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'EditedBy' =>
                    [
                        'class' => 'MODX\\modUser',
                        'local' => 'editedby',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'DeletedBy' =>
                    [
                        'class' => 'MODX\\modUser',
                        'local' => 'deletedby',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'PublishedBy' =>
                    [
                        'class' => 'MODX\\modUser',
                        'local' => 'publishedby',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Template' =>
                    [
                        'class' => 'MODX\\modTemplate',
                        'local' => 'template',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'TemplateVars' =>
                    [
                        'class' => 'MODX\\modTemplateVar',
                        'local' => 'id:template',
                        'foreign' => 'contentid:templateid',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'TemplateVarTemplates' =>
                    [
                        'class' => 'MODX\\modTemplateVarTemplate',
                        'local' => 'template',
                        'foreign' => 'templateid',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'ContentType' =>
                    [
                        'class' => 'MODX\\modContentType',
                        'local' => 'content_type',
                        'foreign' => 'id',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
                'Context' =>
                    [
                        'class' => 'MODX\\modContext',
                        'local' => 'context_key',
                        'foreign' => 'key',
                        'owner' => 'foreign',
                        'cardinality' => 'one',
                    ],
            ],
    ];
}
