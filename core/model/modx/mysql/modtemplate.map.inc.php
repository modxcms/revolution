<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modTemplate']= array (
  'package' => 'modx',
  'table' => 'site_templates',
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
      'precision' => '255',
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
      'precision' => '255',
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
    'Resources' => 
    array (
      'class' => 'modResource',
      'local' => 'id',
      'foreign' => 'template',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'composites' => 
  array (
    'TemplateVarTemplates' => 
    array (
      'class' => 'modTemplateVarTemplate',
      'local' => 'id',
      'foreign' => 'tmplvarid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
