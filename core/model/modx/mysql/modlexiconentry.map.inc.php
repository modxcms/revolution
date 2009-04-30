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
    ),
    'namespace' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => false,
      'default' => 'core',
    ),
    'language' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
      'default' => 'en',
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
if (XPDO_PHP4_MODE) $xpdo_meta_map['modLexiconEntry']['aggregates']= array_merge($xpdo_meta_map['modLexiconEntry']['aggregates'], array_change_key_case($xpdo_meta_map['modLexiconEntry']['aggregates']));
$xpdo_meta_map['modlexiconentry']= & $xpdo_meta_map['modLexiconEntry'];
