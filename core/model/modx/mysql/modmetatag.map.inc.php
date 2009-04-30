<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modMetatag']= array (
  'package' => 'modx',
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
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'tag' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'tagvalue' => 
    array (
      'dbtype' => 'varchar',
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
    'modResourceMetatag' => 
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
if (XPDO_PHP4_MODE) $xpdo_meta_map['modMetatag']['composites']= array_merge($xpdo_meta_map['modMetatag']['composites'], array_change_key_case($xpdo_meta_map['modMetatag']['composites']));
$xpdo_meta_map['modmetatag']= & $xpdo_meta_map['modMetatag'];
