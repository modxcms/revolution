<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modCategory']= array (
  'package' => 'modx',
  'table' => 'categories',
  'fields' => 
  array (
    'parent' => 0,
    'category' => '',
  ),
  'fieldMeta' => 
  array (
    'parent' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'default' => 0,
      'index' => 'index',
    ),
    'category' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '45',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
  ),
  'composites' => 
  array (
    'Children' => 
    array (
      'class' => 'modCategory',
      'local' => 'id',
      'foreign' => 'parent',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Acls' => 
    array (
      'class' => 'modAccessCategory',
      'local' => 'id',
      'foreign' => 'target',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
    'Ancestors' => 
    array (
      'class' => 'modCategoryClosure',
      'local' => 'id',
      'foreign' => 'ancestor',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Descendants' => 
    array (
      'class' => 'modCategoryClosure',
      'local' => 'id',
      'foreign' => 'descendant',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'Parent' => 
    array (
      'class' => 'modCategory',
      'local' => 'parent',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Chunks' => 
    array (
      'class' => 'modChunk',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Snippets' => 
    array (
      'class' => 'modSnippet',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Plugins' => 
    array (
      'class' => 'modPlugin',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Templates' => 
    array (
      'class' => 'modTemplate',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'TemplateVars' => 
    array (
      'class' => 'modTemplateVar',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'PropertySets' => 
    array (
      'class' => 'modPropertySet',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modChunk' => 
    array (
      'class' => 'modChunk',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modSnippet' => 
    array (
      'class' => 'modSnippet',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modPlugin' => 
    array (
      'class' => 'modPlugin',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modTemplate' => 
    array (
      'class' => 'modTemplate',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modTemplateVar' => 
    array (
      'class' => 'modTemplateVar',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modPropertySet' => 
    array (
      'class' => 'modPropertySet',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'validation' => 
  array (
    'rules' => 
    array (
      'category' => 
      array (
        'preventBlank' => 
        array (
          'type' => 'xPDOValidationRule',
          'rule' => 'xPDOMinLengthValidationRule',
          'value' => '1',
          'message' => 'category_err_ns_name',
        ),
      ),
    ),
  ),
);
