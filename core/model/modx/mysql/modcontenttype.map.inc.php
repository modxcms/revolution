<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modContentType']= array (
  'package' => 'modx',
  'table' => 'content_type',
  'fields' => 
  array (
    'name' => NULL,
    'description' => NULL,
    'mime_type' => NULL,
    'file_extensions' => NULL,
    'headers' => NULL,
    'binary' => 0,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'unique',
    ),
    'description' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
      'null' => true,
    ),
    'mime_type' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
    ),
    'file_extensions' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
    ),
    'headers' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'array',
    ),
    'binary' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
  ),
  'aggregates' => 
  array (
    'Resources' => 
    array (
      'class' => 'modResource',
      'local' => 'id',
      'foreign' => 'content_type',
      'owner' => 'local',
      'cardinality' => 'many',
    ),
  ),
  'validation' => 
  array (
    'rules' => 
    array (
      'name' => 
      array (
        'name' => 
        array (
          'type' => 'xPDOValidationRule',
          'rule' => 'xPDOMinLengthValidationRule',
          'value' => '1',
          'message' => 'content_type_err_ns_name',
        ),
      ),
    ),
  ),
);
