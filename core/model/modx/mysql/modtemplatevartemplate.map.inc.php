<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modTemplateVarTemplate']= array (
  'package' => 'modx',
  'table' => 'site_tmplvar_templates',
  'fields' => 
  array (
    'tmplvarid' => 0,
    'templateid' => 0,
    'rank' => 0,
  ),
  'fieldMeta' => 
  array (
    'tmplvarid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'templateid' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'rank' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'aggregates' => 
  array (
    'TemplateVar' => 
    array (
      'class' => 'modTemplateVar',
      'key' => 'id',
      'local' => 'tmplvarid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Template' => 
    array (
      'class' => 'modTemplate',
      'key' => 'id',
      'local' => 'templateid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modTemplateVarTemplate']['aggregates']= array_merge($xpdo_meta_map['modTemplateVarTemplate']['aggregates'], array_change_key_case($xpdo_meta_map['modTemplateVarTemplate']['aggregates']));
$xpdo_meta_map['modtemplatevartemplate']= & $xpdo_meta_map['modTemplateVarTemplate'];
