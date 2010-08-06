<?php
$properties['xpdo_test_path'] = dirname(__FILE__) . '/';

/* mysql */
$properties['mysql_string_dsn_test']= 'mysql:host=localhost;dbname=xpdotest;charset=utf8';
$properties['mysql_string_dsn_nodb']= 'mysql:host=localhost;charset=utf8';
$properties['mysql_string_username']= '';
$properties['mysql_string_password']= '';
$properties['mysql_array_options']= array();
$properties['mysql_array_driverOptions']= array();

/* sqlite */
$properties['sqlite_string_dsn_test']= 'sqlite:' . $properties['xpdo_test_path'] . 'xpdotest';
$properties['sqlite_string_dsn_nodb']= 'sqlite::memory:';
$properties['sqlite_string_username']= '';
$properties['sqlite_string_password']= '';
$properties['sqlite_array_options']= array();
$properties['sqlite_array_driverOptions']= array();

/* PHPUnit test config */
$properties['xpdo_driver']= 'sqlite';
$properties['logLevel']= xPDO::LOG_LEVEL_INFO;