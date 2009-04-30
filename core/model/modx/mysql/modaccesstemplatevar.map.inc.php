<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAccessTemplateVar']= array (
  'package' => 'modx',
  'table' => 'access_templatevars',
  'aggregates' => 
  array (
    'Target' => 
    array (
      'class' => 'modTemplateVar',
      'local' => 'target',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modAccessTemplateVar']['aggregates']= array_merge($xpdo_meta_map['modAccessTemplateVar']['aggregates'], array_change_key_case($xpdo_meta_map['modAccessTemplateVar']['aggregates']));
$xpdo_meta_map['modaccesstemplatevar']= & $xpdo_meta_map['modAccessTemplateVar'];
