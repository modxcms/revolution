<?php

namespace MODX\sqlsrv;


class modTemplateVar extends \MODX\modTemplateVar
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'site_tmplvars',
        'extends' => 'MODX\\modElement',
        'fields' =>
            [
                'type' => '',
                'name' => '',
                'caption' => '',
                'description' => '',
                'editor_type' => 0,
                'category' => 0,
                'locked' => 0,
                'elements' => null,
                'rank' => 0,
                'display' => '',
                'default_text' => null,
                'properties' => null,
                'input_properties' => '',
                'output_properties' => '',
                'static' => 0,
                'static_file' => '',
            ],
        'fieldMeta' =>
            [
                'type' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '20',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'name' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '50',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'unique',
                    ],
                'caption' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '80',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'description' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '255',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
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
                'locked' =>
                    [
                        'dbtype' => 'bit',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'elements' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                    ],
                'rank' =>
                    [
                        'dbtype' => 'int',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'display' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => '20',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                    ],
                'default_text' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'string',
                    ],
                'properties' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'array',
                        'null' => true,
                    ],
                'input_properties' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'array',
                        'null' => false,
                        'default' => '',
                    ],
                'output_properties' =>
                    [
                        'dbtype' => 'nvarchar',
                        'precision' => 'max',
                        'phptype' => 'array',
                        'null' => false,
                        'default' => '',
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
        'fieldAliases' =>
            [
                'content' => 'default_text',
            ],
        'indexes' =>
            [
                'name' =>
                    [
                        'alias' => 'name',
                        'primary' => false,
                        'unique' => true,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'name' =>
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
                'rank' =>
                    [
                        'alias' => 'rank',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'rank' =>
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
                                        'element_class' => 'modTemplateVar',
                                    ],
                            ],
                    ],
                'TemplateVarTemplates' =>
                    [
                        'class' => 'MODX\\modTemplateVarTemplate',
                        'local' => 'id',
                        'foreign' => 'tmplvarid',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'TemplateVarResources' =>
                    [
                        'class' => 'MODX\\modTemplateVarResource',
                        'local' => 'id',
                        'foreign' => 'tmplvarid',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'TemplateVarResourceGroups' =>
                    [
                        'class' => 'MODX\\modTemplateVarResourceGroup',
                        'local' => 'id',
                        'foreign' => 'tmplvarid',
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
            ],
        'validation' =>
            [
                'rules' =>
                    [
                        'name' =>
                            [
                                'invalid' =>
                                    [
                                        'type' => 'preg_match',
                                        'rule' => '/^(?!\\s)[a-zA-Z0-9\\x2d-\\x2f\\x7f-\\xff-_\\s]+(?!\\s)$/',
                                        'message' => 'tv_err_invalid_name',
                                    ],
                                'reserved' =>
                                    [
                                        'type' => 'preg_match',
                                        'rule' => '/^(?!(id|type|contentType|pagetitle|longtitle|description|alias|link_attributes|published|pub_date|unpub_date|parent|isfolder|introtext|content|richtext|template|menuindex|searchable|cacheable|createdby|createdon|editedby|editedon|deleted|deletedby|deletedon|publishedon|publishedby|menutitle|donthit|privateweb|privatemgr|content_dispo|hidemenu|class_key|context_key|content_type|uri|uri_override|hide_children_in_tree|show_in_tree)$)/',
                                        'message' => 'tv_err_reserved_name',
                                    ],
                            ],
                    ],
            ],
    ];
}
