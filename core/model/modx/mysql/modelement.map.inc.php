<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modElement']= array (
  'package' => 'modx',
  'table' => 'site_element',
  'aggregates' => 
  array (
    'PropertySets' => 
    array (
      'class' => 'modElementPropertySet',
      'local' => 'id',
      'foreign' => 'element',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
    'CategoryAcls' => 
    array (
      'class' => 'modAccessCategory',
      'local' => 'category',
      'foreign' => 'target',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
  'composites' => 
  array (
    'Acls' => 
    array (
      'class' => 'modAccessElement',
      'local' => 'id',
      'foreign' => 'target',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
);
