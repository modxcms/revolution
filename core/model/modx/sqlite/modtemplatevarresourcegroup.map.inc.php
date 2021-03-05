<?php
/**
 * @package modx
 * @subpackage sqlite
 */
$xpdo_meta_map['modTemplateVarResourceGroup']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'site_tmplvar_access',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'tmplvarid' => 0,
    'documentgroup' => 0,
  ),
  'fieldMeta' => 
  array (
    'tmplvarid' => 
    array (
      'dbtype' => 'integer',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'documentgroup' => 
    array (
      'dbtype' => 'integer',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'indexes' =>
  array (
    'tmplvar_template' =>
    array (
      'alias' => 'tmplvar_template',
      'type' => 'BTREE',
      'columns' =>
      array (
        'tmplvarid' =>
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'documentgroup' =>
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' =>
  array (
    'TemplateVar' => 
    array (
      'class' => 'modTemplateVar',
      'local' => 'tmplvarid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'ResourceGroup' => 
    array (
      'class' => 'modResourceGroup',
      'local' => 'documentgroup',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
