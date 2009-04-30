<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modEvent']= array (
  'package' => 'modx',
  'table' => 'system_eventnames',
  'fields' => 
  array (
    'name' => '',
    'service' => 0,
    'groupname' => '',
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
      'index' => 'unique',
    ),
    'service' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'groupname' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'aggregates' => 
  array (
    'modPluginEvent' => 
    array (
      'class' => 'modPluginEvent',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'evtid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modEvent']['aggregates']= array_merge($xpdo_meta_map['modEvent']['aggregates'], array_change_key_case($xpdo_meta_map['modEvent']['aggregates']));
$xpdo_meta_map['modevent']= & $xpdo_meta_map['modEvent'];
