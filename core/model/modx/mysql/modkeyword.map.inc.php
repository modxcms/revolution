<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modKeyword']= array (
  'package' => 'modx',
  'table' => 'site_keywords',
  'fields' => 
  array (
    'keyword' => '',
  ),
  'fieldMeta' => 
  array (
    'keyword' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
  ),
  'composites' => 
  array (
    'ResourceKeywords' => 
    array (
      'class' => 'modResourceKeyword',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'keyword_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modKeyword']['composites']= array_merge($xpdo_meta_map['modKeyword']['composites'], array_change_key_case($xpdo_meta_map['modKeyword']['composites']));
$xpdo_meta_map['modkeyword']= & $xpdo_meta_map['modKeyword'];
