<?php
use xPDO\xPDO;
/* define some properties */
$properties['cache_path'] = MODX_CORE_PATH . '/' . (MODX_CONFIG_KEY === 'config' ? '' : MODX_CONFIG_KEY . '/') . 'cache/';

/* driver-specific connection properties */
/* mysql */
$properties['mysql_string_dsn_test']= 'mysql:host=localhost;dbname=revo_test;charset=utf8';
$properties['mysql_string_dsn_nodb']= 'mysql:host=localhost;charset=utf8';
$properties['mysql_string_dsn_error']= 'mysql:host= nonesuchhost;dbname=nonesuchdb';
$properties['mysql_string_username']= '';
$properties['mysql_string_password']= '';
$properties['mysql_array_options']= [
    xPDO::OPT_CACHE_PATH => $properties['cache_path'],
    xPDO::OPT_HYDRATE_FIELDS => true,
    xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
    xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
];
$properties['mysql_array_driverOptions']= [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_SILENT];

/* PHPUnit test config */
$properties['xpdo_driver']= 'mysql';
$properties['logLevel']= xPDO::LOG_LEVEL_INFO;
/* $properties['debug']= -1; */
