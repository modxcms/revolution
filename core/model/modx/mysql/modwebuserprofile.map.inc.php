<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modWebUserProfile']= array (
  'package' => 'modx',
  'table' => 'web_user_attributes',
  'fields' => 
  array (
    'internalKey' => 0,
    'fullname' => '',
    'role' => 0,
    'email' => '',
    'phone' => '',
    'mobilephone' => '',
    'blocked' => 0,
    'blockeduntil' => 0,
    'blockedafter' => 0,
    'logincount' => 0,
    'lastlogin' => 0,
    'thislogin' => 0,
    'failedlogincount' => 0,
    'sessionid' => '',
    'dob' => 0,
    'gender' => 0,
    'country' => '',
    'state' => '',
    'zip' => '',
    'fax' => '',
    'photo' => '',
    'comment' => '',
  ),
  'fieldMeta' => 
  array (
    'internalKey' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'fullname' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'role' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'email' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'phone' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'mobilephone' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'blocked' => 
    array (
      'dbtype' => 'int',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'blockeduntil' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'blockedafter' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'logincount' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'lastlogin' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'thislogin' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'failedlogincount' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'sessionid' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'dob' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'gender' => 
    array (
      'dbtype' => 'int',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'country' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '5',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'state' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '25',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'zip' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '25',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'fax' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'photo' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'comment' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'aggregates' => 
  array (
    'modWebUser' => 
    array (
      'class' => 'modWebUser',
      'key' => 'internalKey',
      'local' => 'internalKey',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modUserRole' => 
    array (
      'class' => 'modUserRole',
      'key' => 'role',
      'local' => 'role',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
