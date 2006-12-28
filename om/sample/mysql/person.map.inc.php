<?php
/**
 * Metadata map for the Person class.
 * 
 * Note that all fields from parent classes are inherited.
 * @package xpdo.om.sample.mysql
 */
$xpdo_meta_map['Person']['table']= 'person';
$xpdo_meta_map['Person']['fields']= array (
   'first_name' => '',
   'last_name' => '',
   'middle_name' => '',
   'date_modified' => 'CURRENT_TIMESTAMP',
   'dob' => null,
   'gender' => '',
   'blood_type' => '',
   'username' => '',
   'password' => '',
   'security_level' => 0,
);
$xpdo_meta_map['Person']['fieldMeta']= array (
   'first_name' => array('dbtype' => 'VARCHAR', 'precision' => '100', 'phptype' => 'string', 'null' => false, ),
   'last_name' => array('dbtype' => 'VARCHAR', 'precision' => '100', 'phptype' => 'string', 'null' => false, ),
   'middle_name' => array('dbtype' => 'VARCHAR', 'precision' => '100', 'phptype' => 'string', 'null' => false, ),
   'date_modified' => array('dbtype' => 'TIMESTAMP', 'phptype' => 'timestamp', 'attributes' => 'ON UPDATE CURRENT_TIMESTAMP' ),
   'dob' => array('dbtype' => 'DATE', 'phptype' => 'string', ),
   'gender' => array('dbtype' => 'ENUM', 'precision' => '\'\', \'M\',\'F\'', 'phptype' => 'string', 'null' => false, ),
   'blood_type' => array('dbtype' => 'ENUM', 'precision' => '\'\', \'A+\',\'A-\',\'B+\',\'B-\',\'AB+\',\'AB-\',\'O+\',\'O-\'', 'phptype' => 'string', 'null' => false, ),
   'username' => array('dbtype' => 'VARCHAR', 'precision' => '255', 'phptype' => 'string', 'null' => false, 'index' => 'unique', ),
   'password' => array('dbtype' => 'VARCHAR', 'precision' => '255', 'phptype' => 'password', 'null' => false, ),
   'security_level' => array('dbtype' => 'TINYINT', 'phptype' => 'integer', 'null' => false, ),
);
$xpdo_meta_map['Person']['composites']= array (
    'PersonPhone' => array('id' => array('local' => 'id', 'foreign' => 'person', 'cardinality' => 'many', ), ),
);
$xpdo_meta_map['Person']['aggregates']= array ();
$xpdo_meta_map['person']= & $xpdo_meta_map['Person'];
?>