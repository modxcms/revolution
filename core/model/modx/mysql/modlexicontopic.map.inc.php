<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modLexiconTopic']= array (
  'package' => 'modx',
  'version' => '1.1',
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
  'indexes' => 
  array (
    'foci' => 
    array (
      'alias' => 'foci',
      'primary' => false,
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
        'namespace' => 
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
