<?php
/**
 * Metadata map for the xPDOSimpleObject class, which provides an integer
 * primary key column which uses MySQL's native auto_increment primary key
 * generation facilities.
 * @package xpdo.om.mysql
 */
$xpdo_meta_map['xPDOSimpleObject']['table']= null;
$xpdo_meta_map['xPDOSimpleObject']['fields']= array (
   'id' => null,
);
$xpdo_meta_map['xPDOSimpleObject']['fieldMeta']= array (
   'id' => array('dbtype' => 'INTEGER', 'phptype' => 'integer', 'null' => false, 'index' => 'pk', 'generated' => 'native', 'attributes' => 'unsigned', ),
);
$xpdo_meta_map['xpdosimpleobject']= & $xpdo_meta_map['xPDOSimpleObject'];
?>