<?php
/**
 * Metadata map for the PersonPhone class.
 * 
 * Note that all fields from parent classes are inherited.
 * @package xpdo.om.sample.mysql
 */
$xpdo_meta_map['PersonPhone']['table']= 'person_phone';
$xpdo_meta_map['PersonPhone']['fields']= array (
   'person' => null,
   'phone' => null,
   'is_primary' => 0,
);
$xpdo_meta_map['PersonPhone']['fieldMeta']= array (
   'person' => array('dbtype' => 'INTEGER', 'phptype' => 'integer', 'index' => 'pk', ),
   'phone' => array('dbtype' => 'INTEGER', 'phptype' => 'integer', 'index' => 'pk', ),
   'is_primary' => array('dbtype' => 'BINARY', 'phptype' => 'boolean', 'null' => false, ),
);
$xpdo_meta_map['PersonPhone']['composites']= array (
    'Phone' => array('phone' => array('local' => 'phone', 'foreign' => 'id', 'cardinality' => 'one', ), ),
);
$xpdo_meta_map['PersonPhone']['aggregates']= array (
    'Person' => array('person' => array('local' => 'person', 'foreign' => 'id', 'cardinality' => 'one', ), ),
);
$xpdo_meta_map['personphone']= & $xpdo_meta_map['PersonPhone'];
?>