<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modKeyword']= array (
  'package' => 'modx',
  'version' => '1.1',
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
  'indexes' => 
  array (
    'keyword' => 
    array (
      'alias' => 'keyword',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'keyword' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
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
