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
  'aggregates' => 
  array (
    'modLexiconEntry' => 
    array (
      'class' => 'modLexiconEntry',
      'local' => 'name',
      'foreign' => 'language',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modLexiconLanguage']['aggregates']= array_merge($xpdo_meta_map['modLexiconLanguage']['aggregates'], array_change_key_case($xpdo_meta_map['modLexiconLanguage']['aggregates']));
$xpdo_meta_map['modlexiconlanguage']= & $xpdo_meta_map['modLexiconLanguage'];
