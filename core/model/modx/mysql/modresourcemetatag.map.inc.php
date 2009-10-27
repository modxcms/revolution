<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modResourceMetatag']= array (
  'package' => 'modx',
  'table' => 'site_content_metatags',
  'fields' => 
  array (
    'content_id' => 0,
    'metatag_id' => 0,
  ),
  'fieldMeta' => 
  array (
    'content_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'metatag_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
  ),
  'aggregates' => 
  array (
    'Resource' => 
    array (
      'class' => 'modResource',
      'key' => 'id',
      'local' => 'content_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Metatag' => 
    array (
      'class' => 'modMetatag',
      'key' => 'id',
      'local' => 'metatag_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
