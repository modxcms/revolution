<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modAccessPolicyTemplate']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'access_policy_templates',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'template_group' => 0,
    'name' => '',
    'description' => NULL,
    'lexicon' => 'permissions',
  ),
  'fieldMeta' => 
  array (
    'template_group' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'name' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'description' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'string',
    ),
    'lexicon' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => 'permissions',
    ),
  ),
  'composites' => 
  array (
    'Permissions' => 
    array (
      'class' => 'modAccessPermission',
      'local' => 'id',
      'foreign' => 'template',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
    'Policies' => 
    array (
      'class' => 'modAccessPolicy',
      'local' => 'id',
      'foreign' => 'template',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
  'aggregates' => 
  array (
    'TemplateGroup' => 
    array (
      'class' => 'modAccessPolicyTemplateGroup',
      'local' => 'template_group',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
