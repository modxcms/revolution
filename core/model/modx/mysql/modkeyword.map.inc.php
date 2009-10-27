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
