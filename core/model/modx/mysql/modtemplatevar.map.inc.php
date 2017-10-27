<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modTemplateVar']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'site_tmplvars',
  'extends' => 'modElement',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'type' => '',
    'name' => '',
    'caption' => '',
    'description' => '',
    'editor_type' => 0,
    'category' => 0,
    'locked' => 0,
    'elements' => NULL,
    'rank' => 0,
    'display' => '',
    'default_text' => NULL,
    'properties' => NULL,
    'input_properties' => NULL,
    'output_properties' => NULL,
    'static' => 0,
    'static_file' => '',
  ),
  'fieldMeta' => 
  array (
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
    'caption' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '80',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'description' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '191',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
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
    'elements' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
    ),
    'rank' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'display' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'default_text' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
    ),
    'properties' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'array',
      'null' => true,
    ),
    'input_properties' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'array',
      'null' => true,
    ),
    'output_properties' => 
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
  'fieldAliases' => 
  array (
    'content' => 'default_text',
  ),
  'indexes' => 
  array (
    'name' => 
    array (
      'alias' => 'name',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'name' => 
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
    'rank' => 
    array (
      'alias' => 'rank',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'rank' => 
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
      'class' => 'modElementPropertySet',
      'local' => 'id',
      'foreign' => 'element',
      'owner' => 'local',
      'cardinality' => 'many',
      'criteria' => 
      array (
        'foreign' => 
        array (
          'element_class' => 'modTemplateVar',
        ),
      ),
    ),
    'TemplateVarTemplates' => 
    array (
      'class' => 'modTemplateVarTemplate',
      'local' => 'id',
      'foreign' => 'tmplvarid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'TemplateVarResources' => 
    array (
      'class' => 'modTemplateVarResource',
      'local' => 'id',
      'foreign' => 'tmplvarid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'TemplateVarResourceGroups' => 
    array (
      'class' => 'modTemplateVarResourceGroup',
      'local' => 'id',
      'foreign' => 'tmplvarid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'Category' => 
    array (
      'class' => 'modCategory',
      'local' => 'category',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
  'validation' => 
  array (
    'rules' => 
    array (
      'name' => 
      array (
        'invalid' => 
        array (
          'type' => 'preg_match',
          'rule' => '/^(?!\\s)[a-zA-Z0-9\\x2d-\\x2f\\x7f-\\xff-_\\s]+(?!\\s)$/',
          'message' => 'tv_err_invalid_name',
        ),
        'reserved' => 
        array (
          'type' => 'preg_match',
          'rule' => '/^(?!(id|type|contentType|pagetitle|longtitle|description|alias|link_attributes|published|pub_date|unpub_date|parent|isfolder|introtext|content|richtext|template|menuindex|searchable|cacheable|createdby|createdon|editedby|editedon|deleted|deletedby|deletedon|publishedon|publishedby|menutitle|donthit|privateweb|privatemgr|content_dispo|hidemenu|class_key|context_key|content_type|uri|uri_override|hide_children_in_tree|show_in_tree)$)/',
          'message' => 'tv_err_reserved_name',
        ),
      ),
    ),
  ),
);
