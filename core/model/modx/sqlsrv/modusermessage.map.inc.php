<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
$xpdo_meta_map['modUserMessage']= array (
  'package' => 'modx',
  'version' => '1.1',
  'table' => 'user_messages',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'type' => '',
    'subject' => '',
    'message' => '',
    'sender' => 0,
    'recipient' => 0,
    'private' => 0,
    'date_sent' => '0000-00-00 00:00:00',
    'read' => 0,
  ),
  'fieldMeta' => 
  array (
    'type' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '15',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'subject' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'message' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => 'max',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'sender' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'recipient' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'private' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'date_sent' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
      'default' => '0000-00-00 00:00:00',
    ),
    'read' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'aggregates' => 
  array (
    'Sender' => 
    array (
      'class' => 'modUser',
      'local' => 'sender',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Recipient' => 
    array (
      'class' => 'modUser',
      'local' => 'recipient',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
