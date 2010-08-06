<?php
/**
 * Metadata map for the xPDOSample class.
 *
 * Note that all fields from parent classes are inherited.
 * @package sample.mysql
 */
$xpdo_meta_map['xPDOSample']['table']= 'xpdosample';
$xpdo_meta_map['xPDOSample']['fields']= array (
    'parent' => 0,
    'unique_varchar' => '',
    'varchar' => '',
    'text' => null,
    'timestamp' => 'CURRENT_TIMESTAMP',
    'unix_timestamp' => 0,
    'date_time' => null,
    'date' => null,
    'enum' => '',
    'password' => '',
    'integer' => 1,
    'float' => 1.0123,
    'boolean' => 0,
);
$xpdo_meta_map['xPDOSample']['fieldMeta']= array (
    'parent' => array('dbtype' => 'INTEGER', 'phptype' => 'integer', 'null' => 'false', 'default' => 0 ),
    'unique_varchar' => array('dbtype' => 'VARCHAR', 'precision' => '255', 'phptype' => 'string', 'null' => 'false', 'index' => 'unique', ),
    'varchar' => array('dbtype' => 'VARCHAR', 'precision' => '100', 'phptype' => 'string', 'null' => 'false', ),
    'text' => array('dbtype' => 'TEXT', 'phptype' => 'string', 'null' => 'true', ),
    'timestamp' => array('dbtype' => 'TIMESTAMP', 'phptype' => 'timestamp', 'null' => 'false', 'default' => 'CURRENT_TIMESTAMP', 'attributes' => 'ON UPDATE CURRENT_TIMESTAMP' ),
    'unix_timestamp' => array('dbtype' => 'INTEGER', 'phptype' => 'timestamp', 'null' => 'false', 'default' => 0 ),
    'date_time' => array('dbtype' => 'DATETIME', 'phptype' => 'datetime', 'null' => 'true', 'default' => 'NULL' ),
    'date' => array('dbtype' => 'DATE', 'phptype' => 'date', ),
    'enum' => array('dbtype' => 'ENUM', 'precision' => '\'\', \'T\',\'F\'', 'phptype' => 'string', 'null' => 'false', ),
    'password' => array('dbtype' => 'VARCHAR', 'precision' => '255', 'phptype' => 'password', 'null' => 'false', ),
    'integer' => array('dbtype' => 'INTEGER', 'phptype' => 'integer', 'null' => 'false', ),
    'float' => array('dbtype' => 'DECIMAL', 'precision' => '10,5', 'phptype' => 'float', 'null' => 'false', 'default' => 1.0123 ),
    'boolean' => array('dbtype' => 'TINYINT', 'precision' => '1', 'phptype' => 'boolean', 'null' => 'false', ),
);
$xpdo_meta_map['xPDOSample']['aggregates']= array (
    'Children' => array('class' => 'xPDOSample', 'local' => 'id', 'foreign' => 'parent', 'cardinality' => 'many', ),
    'Parent' => array('class' => 'xPDOSample', 'local' => 'parent', 'foreign' => 'id', 'cardinality' => 'one', ),
);
$xpdo_meta_map['xPDOSample']['composites']= array ();
