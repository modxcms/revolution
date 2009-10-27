<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modNamespace']= array (
  'package' => 'modx',
  'table' => 'namespaces',
  'fields' => 
  array (
    'name' => '',
    'path' => '',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'pk',
    ),
    'path' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'default' => '',
    ),
  ),
  'composites' => 
  array (
    'LexiconTopics' => 
    array (
      'class' => 'modLexiconTopic',
      'local' => 'name',
      'foreign' => 'namespace',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
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
