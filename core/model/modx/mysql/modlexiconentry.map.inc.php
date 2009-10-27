<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modLexiconEntry']= array (
  'package' => 'modx',
  'table' => 'lexicon_entries',
  'fields' => 
  array (
    'name' => '',
    'value' => '',
    'topic' => 1,
    'namespace' => 'core',
    'language' => 'en',
    'createdon' => NULL,
    'editedon' => NULL,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'value' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'topic' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
      'index' => 'index',
    ),
    'namespace' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => false,
      'default' => 'core',
      'index' => 'index',
    ),
    'language' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
      'default' => 'en',
      'index' => 'index',
    ),
    'createdon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
    ),
    'editedon' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => false,
      'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
    ),
  ),
  'aggregates' => 
  array (
    'Namespace' => 
    array (
      'class' => 'modNamespace',
      'local' => 'namespace',
      'foreign' => 'name',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Topic' => 
    array (
      'class' => 'modLexiconTopic',
      'local' => 'topic',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Language' => 
    array (
      'class' => 'modLexiconLanguage',
      'local' => 'language',
      'foreign' => 'name',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modNamespace' => 
    array (
      'class' => 'modNamespace',
      'local' => 'namespace',
      'foreign' => 'name',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modLexiconTopic' => 
    array (
      'class' => 'modLexiconTopic',
      'local' => 'topic',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modLexiconLanguage' => 
    array (
      'class' => 'modLexiconLanguage',
      'local' => 'language',
      'foreign' => 'name',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
