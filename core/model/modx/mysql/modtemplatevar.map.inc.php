<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modTemplateVar']= array (
  'package' => 'modx',
  'table' => 'site_tmplvars',
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
    'display_params' => NULL,
    'default_text' => NULL,
    'properties' => NULL,
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
      'precision' => '255',
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
    'display_params' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
    ),
    'default_text' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
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
  ),
  'composites' => 
  array (
    'TemplateVarTemplates' => 
    array (
      'class' => 'modTemplateVarTemplate',
      'local' => 'id',
      'foreign' => 'tmplvarid',
      'cardinality' => 'many',
      'owner' => 'foreign',
    ),
    'TemplateVarResources' => 
    array (
      'class' => 'modTemplateVarResource',
      'local' => 'id',
      'foreign' => 'tmplvarid',
      'cardinality' => 'many',
      'owner' => 'foreign',
    ),
    'TemplateVarResourceGroups' => 
    array (
      'class' => 'modTemplateVarResourceGroup',
      'local' => 'id',
      'foreign' => 'tmplvarid',
      'cardinality' => 'many',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modTemplateVar']['aggregates']= array_merge($xpdo_meta_map['modTemplateVar']['aggregates'], array_change_key_case($xpdo_meta_map['modTemplateVar']['aggregates']));
if (XPDO_PHP4_MODE) $xpdo_meta_map['modTemplateVar']['composites']= array_merge($xpdo_meta_map['modTemplateVar']['composites'], array_change_key_case($xpdo_meta_map['modTemplateVar']['composites']));
$xpdo_meta_map['modtemplatevar']= & $xpdo_meta_map['modTemplateVar'];
