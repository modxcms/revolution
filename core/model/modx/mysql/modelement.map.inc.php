<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modElement']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'site_element',
  'extends' => 'modAccessibleSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'createdby' => 0,
    'createdon' => 0,
    'editedby' => 0,
    'editedon' => 0,
    'source' => 0,
    'property_preprocess' => 0,
  ),
  'fieldMeta' => 
  array (
    'createdby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'createdon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'editedby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'editedon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'source' => 
    array (
      'dbtype' => 'int',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'fk',
    ),
    'property_preprocess' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
  ),
  'indexes' =>
  array (
    'editedon' =>
    array (
      'alias' => 'editedon',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' =>
      array (
        'editedon' =>
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
    'Acls' => 
    array (
      'class' => 'modAccessElement',
      'local' => 'id',
      'foreign' => 'target',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
  'aggregates' => 
  array (
    'CategoryAcls' => 
    array (
      'class' => 'modAccessCategory',
      'local' => 'category',
      'foreign' => 'target',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
    'Source' => 
    array (
      'class' => 'sources.modMediaSource',
      'local' => 'source',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
