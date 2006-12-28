<?php
/**
 * Metadata map for the xPDOSample class.
 * 
 * Note that all fields from parent classes are inherited.
 * @package xpdo.om.sample.mysql
 */
$xpdo_meta_map['xPDOSample']['table']= 'xpdosample';
$xpdo_meta_map['xPDOSample']['fields']= array (
    'parent' => null,
    'unique_varchar' => '',
    'varchar' => '',
    'text' => null,
    'timestamp' => null,
    'unix_timestamp' => 0,
    'date_time' => null,
    'date' => null,
    'enum' => '',
    'password' => '',
    'integer' => 1,
    'boolean' => 0,
);
$xpdo_meta_map['xPDOSample']['fieldMeta']= array (
    'parent' => array('dbtype' => 'INTEGER', 'phptype' => 'integer', 'null' => 'true', ),
    'unique_varchar' => array('dbtype' => 'VARCHAR', 'precision' => '255', 'phptype' => 'string', 'null' => 'false', 'index' => 'unique', ),
    'varchar' => array('dbtype' => 'VARCHAR', 'precision' => '100', 'phptype' => 'string', 'null' => 'false', ),
    'text' => array('dbtype' => 'TEXT', 'phptype' => 'string', 'null' => 'true', ),
    'timestamp' => array('dbtype' => 'TIMESTAMP', 'phptype' => 'timestamp' ),
    'unix_timestamp' => array('dbtype' => 'INTEGER', 'phptype' => 'timestamp' ),
    'date_time' => array('dbtype' => 'DATETIME', 'phptype' => 'datetime', ),
    'date' => array('dbtype' => 'DATE', 'phptype' => 'date', ),
    'enum' => array('dbtype' => 'ENUM', 'precision' => '\'\', \'T\',\'F\'', 'phptype' => 'string', 'null' => 'false', ),
    'password' => array('dbtype' => 'VARCHAR', 'precision' => '255', 'phptype' => 'password', 'null' => 'false', ),
    'integer' => array('dbtype' => 'INTEGER', 'phptype' => 'integer', 'null' => 'false', ),
    'boolean' => array('dbtype' => 'BINARY', 'phptype' => 'boolean', 'null' => 'false', ),
);
$xpdo_meta_map['xPDOSample']['aggregates']= array (
    'xPDOSample' => array(
        'children' => array('local' => 'id', 'foreign' => 'parent', 'cardinality' => 'many', ), 
        'parent' => array('local' => 'parent', 'foreign' => 'id', 'cardinality' => 'one', ), 
    ),
);
$xpdo_meta_map['xPDOSample']['composites']= array ();
$xpdo_meta_map['xpdosample']= & $xpdo_meta_map['xPDOSample'];
?>