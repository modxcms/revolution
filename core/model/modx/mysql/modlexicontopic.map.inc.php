<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modLexiconTopic']= array (
  'package' => 'modx',
  'table' => 'lexicon_topics',
  'fields' => 
  array (
    'name' => '',
    'namespace' => 'core',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
      'indexgrp' => 'foci',
    ),
    'namespace' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => false,
      'default' => 'core',
      'index' => 'unique',
      'indexgrp' => 'foci',
    ),
  ),
  'composites' => 
  array (
    'Entries' => 
    array (
      'class' => 'modLexiconEntry',
      'local' => 'id',
      'foreign' => 'topic',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modLexiconEntry' => 
    array (
      'class' => 'modLexiconEntry',
      'local' => 'id',
      'foreign' => 'topic',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'Namespace' => 
    array (
      'class' => 'modNamespace',
      'local' => 'namespace',
      'foreign' => 'key',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modNamespace' => 
    array (
      'class' => 'modNamespace',
      'local' => 'namespace',
      'foreign' => 'key',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modLexiconTopic']['aggregates']= array_merge($xpdo_meta_map['modLexiconTopic']['aggregates'], array_change_key_case($xpdo_meta_map['modLexiconTopic']['aggregates']));
if (XPDO_PHP4_MODE) $xpdo_meta_map['modLexiconTopic']['composites']= array_merge($xpdo_meta_map['modLexiconTopic']['composites'], array_change_key_case($xpdo_meta_map['modLexiconTopic']['composites']));
$xpdo_meta_map['modlexicontopic']= & $xpdo_meta_map['modLexiconTopic'];
