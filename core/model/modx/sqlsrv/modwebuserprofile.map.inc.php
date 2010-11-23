<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modWebUserProfile']= array (
  'package' => 'modx',
  'version' => '1.1',
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
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'fullname' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'role' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'email' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'phone' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'mobilephone' => 
    array (
      'dbtype' => 'nvarchar',
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
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'blockedafter' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'logincount' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'lastlogin' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'thislogin' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'failedlogincount' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'sessionid' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'dob' => 
    array (
      'dbtype' => 'int',
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
      'dbtype' => 'nvarchar',
      'precision' => '5',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'state' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '25',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'zip' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '25',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'fax' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'photo' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'comment' => 
    array (
      'dbtype' => 'nvarchar',
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
