<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modLexiconLanguage']= array (
  'package' => 'modx',
  'table' => 'lexicon_languages',
  'fields' => 
  array (
    'name' => '',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'pk',
    ),
  ),
  'composites' => 
  array (
    'Entries' => 
    array (
      'class' => 'modLexiconEntry',
      'local' => 'name',
      'foreign' => 'language',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
