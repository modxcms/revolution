<?php
$properties['xpdo_test_path'] = dirname(__FILE__) . '/';

/* mysql */
$properties['mysql_string_dsn_test']= 'mysql:host=localhost;dbname=xpdotest;charset=utf8';
$properties['mysql_string_dsn_nodb']= 'mysql:host=localhost;charset=utf8';
$properties['mysql_string_dsn_error']= 'mysql:host= nonesuchhost;dbname=nonesuchdb';
$properties['mysql_string_username']= '';
$properties['mysql_string_password']= '';
$properties['mysql_array_options']= array(
    xPDO::OPT_CACHE_PATH => XPDO_CORE_PATH.'cache/',
    xPDO::OPT_HYDRATE_FIELDS => true,
    xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
    xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
);
$properties['mysql_array_driverOptions']= array();

/* sqlite */
$properties['sqlite_string_dsn_test']= 'sqlite:' . $properties['xpdo_test_path'] . 'db/xpdotest';
$properties['sqlite_string_dsn_nodb']= 'sqlite::memory:';
$properties['sqlite_string_dsn_error']= 'sqlite:db/';
$properties['sqlite_string_username']= '';
$properties['sqlite_string_password']= '';
$properties['sqlite_array_options']= array(
    xPDO::OPT_CACHE_PATH => XPDO_CORE_PATH.'cache/',
    xPDO::OPT_HYDRATE_FIELDS => true,
    xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
    xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
);
$properties['sqlite_array_driverOptions']= array();

/* PHPUnit test config */
$properties['xpdo_driver']= 'mysql';
$properties['logLevel']= xPDO::LOG_LEVEL_INFO;