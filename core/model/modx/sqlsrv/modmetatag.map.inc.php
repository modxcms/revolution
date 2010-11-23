<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modMetatag']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'site_metatags',
  'fields' => 
  array (
    'name' => '',
    'tag' => '',
    'tagvalue' => '',
    'http_equiv' => 0,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'tag' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'tagvalue' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'http_equiv' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'composites' => 
  array (
    'ResourceMetatags' => 
    array (
      'class' => 'modResourceMetatag',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'metatag_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
