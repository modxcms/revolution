<?php
$xpdo_meta_map['Person']= array (
  'package' => 'sample',
  'table' => 'person',
  'fields' =>
  array (
    'first_name' => NULL,
    'last_name' => NULL,
    'middle_name' => NULL,
    'date_modified' => 'CURRENT_TIMESTAMP',
    'dob' => NULL,
    'gender' => '',
    'blood_type' => '',
    'username' => NULL,
    'password' => NULL,
    'security_level' => NULL,
  ),
  'fieldMeta' =>
  array (
    'first_name' =>
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => 'false',
    ),
    'last_name' =>
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => 'false',
    ),
    'middle_name' =>
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => 'false',
    ),
    'date_modified' =>
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => 'false',
      'default' => 'CURRENT_TIMESTAMP',
      'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
    ),
    'dob' =>
    array (
      'dbtype' => 'date',
      'phptype' => 'date',
      'null' => 'true',
    ),
    'gender' =>
    array (
      'dbtype' => 'enum',
      'precision' => '\'\',\'M\',\'F\'',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
    ),
    'blood_type' =>
    array (
      'dbtype' => 'enum',
      'precision' => '\'\',\'A+\',\'A-\',\'B+\',\'B-\',\'AB+\',\'AB-\',\'O+\',\'O-\'',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
    ),
    'username' =>
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => 'false',
      'index' => 'unique',
    ),
    'password' =>
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => 'false',
    ),
    'security_level' =>
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => 'false',
    ),
  ),
  'composites' =>
  array (
    'PersonPhone' =>
    array (
      'class' => 'PersonPhone',
      'local' => 'id',
      'foreign' => 'person',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
