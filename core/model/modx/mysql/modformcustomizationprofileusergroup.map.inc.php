<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modFormCustomizationProfileUserGroup']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'fc_profiles_usergroups',
  'extends' => 'xPDOObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'usergroup' => 0,
    'profile' => 0,
  ),
  'fieldMeta' => 
  array (
    'usergroup' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'profile' => 
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
        'usergroup' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'profile' => 
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
    'UserGroup' => 
    array (
      'class' => 'modUserGroup',
      'local' => 'usergroup',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Profile' => 
    array (
      'class' => 'modFormCustomizationProfile',
      'local' => 'profile',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
