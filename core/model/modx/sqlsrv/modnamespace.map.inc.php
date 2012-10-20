<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modNamespace']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'namespaces',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'name' => '',
    'path' => '',
    'assets_path' => '',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'pk',
    ),
    'path' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'string',
      'default' => '',
    ),
    'assets_path' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'string',
      'default' => '',
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
        'name' => 
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
    'LexiconEntries' => 
    array (
      'class' => 'modLexiconEntry',
      'local' => 'name',
      'foreign' => 'namespace',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'SystemSettings' => 
    array (
      'class' => 'modSystemSetting',
      'local' => 'name',
      'foreign' => 'namespace',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'ContextSettings' => 
    array (
      'class' => 'modContextSetting',
      'local' => 'name',
      'foreign' => 'namespace',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'UserSettings' => 
    array (
      'class' => 'modUserSetting',
      'local' => 'name',
      'foreign' => 'namespace',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Actions' => 
    array (
      'class' => 'modAction',
      'local' => 'name',
      'foreign' => 'namespace',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
