<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modAccessPolicyTemplateGroup']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'access_policy_template_groups',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => '',
    'description' => NULL,
  ),
  'fieldMeta' => 
  array (
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
  ),
  'composites' => 
  array (
    'Templates' => 
    array (
      'class' => 'modAccessPolicyTemplate',
      'local' => 'id',
      'foreign' => 'template_group',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
);
