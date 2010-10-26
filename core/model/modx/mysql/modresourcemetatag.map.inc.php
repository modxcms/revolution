<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modResourceMetatag']= array (
  'package' => 'modx',
  'version' => '1.1',
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
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'content_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'metatag_id' => 
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
