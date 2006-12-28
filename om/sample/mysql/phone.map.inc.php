<?php
/**
 * Metadata map for the Phone class.
 * 
 * Note that all fields from parent classes are inherited.
 * @package xpdo.om.sample.mysql
 */
$xpdo_meta_map['Phone']['table']= 'phone';
$xpdo_meta_map['Phone']['fields']= array (
   'type' => '',   
   'number' => '',
   'date_modified' => 'CURRENT_TIMESTAMP',
);
$xpdo_meta_map['Phone']['fieldMeta']= array (
   'type' => array('dbtype' => 'ENUM', 'phptype' => 'string', 'precision' => '\'\', \'home\', \'work\', \'mobile\'', 'null' => false, ),
   'number' => array('dbtype' => 'VARCHAR', 'precision' => '20', 'phptype' => 'string', 'null' => false, ),
   'date_modified' => array('dbtype' => 'TIMESTAMP', 'phptype' => 'timestamp', 'attributes' => 'ON UPDATE CURRENT_TIMESTAMP'),
);
$xpdo_meta_map['Phone']['composites']= array (
    'PersonPhone' => array('id' => array('local' => 'id', 'foreign' => 'phone', 'cardinality' => 'one', ), ),
);
$xpdo_meta_map['Phone']['aggregates']= array ();
$xpdo_meta_map['phone']= & $xpdo_meta_map['Phone'];
?>