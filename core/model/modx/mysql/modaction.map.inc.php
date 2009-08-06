<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modAction']= array (
  'package' => 'modx',
  'table' => 'actions',
  'fields' => 
  array (
    'namespace' => 'core',
    'parent' => 0,
    'controller' => NULL,
    'haslayout' => 1,
    'lang_topics' => NULL,
    'assets' => NULL,
  ),
  'fieldMeta' => 
  array (
    'namespace' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'core',
    ),
    'parent' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'controller' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'haslayout' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'lang_topics' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'assets' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'aggregates' => 
  array (
    'Namespace' => 
    array (
      'class' => 'modNamespace',
      'local' => 'namespace',
      'foreign' => 'name',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
    'Parent' => 
    array (
      'class' => 'modAction',
      'local' => 'parent',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
    'Children' => 
    array (
      'class' => 'modAction',
      'local' => 'id',
      'foreign' => 'parent',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
  'composites' => 
  array (
    'Menus' => 
    array (
      'class' => 'modMenu',
      'local' => 'id',
      'foreign' => 'action',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
    'Acls' => 
    array (
      'class' => 'modAccessAction',
      'local' => 'id',
      'foreign' => 'target',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modAction']['aggregates']= array_merge($xpdo_meta_map['modAction']['aggregates'], array_change_key_case($xpdo_meta_map['modAction']['aggregates']));
if (XPDO_PHP4_MODE) $xpdo_meta_map['modAction']['composites']= array_merge($xpdo_meta_map['modAction']['composites'], array_change_key_case($xpdo_meta_map['modAction']['composites']));
$xpdo_meta_map['modaction']= & $xpdo_meta_map['modAction'];
