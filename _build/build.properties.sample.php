<?php
/* define some properties */
$properties['cache_path'] = MODX_CORE_PATH . '/' . (MODX_CONFIG_KEY === 'config' ? '' : MODX_CONFIG_KEY . '/') . 'cache/';

/* driver-specific connection properties */
/* mysql */
$properties['mysql_string_dsn_test']= 'mysql:host=localhost;dbname=revo_test;charset=utf8';
$properties['mysql_string_dsn_nodb']= 'mysql:host=localhost;charset=utf8';
$properties['mysql_string_dsn_error']= 'mysql:host= nonesuchhost;dbname=nonesuchdb';
$properties['mysql_string_username']= '';
$properties['mysql_string_password']= '';
$properties['mysql_array_options']= array(
    xPDO::OPT_CACHE_PATH => $properties['cache_path'],
    xPDO::OPT_HYDRATE_FIELDS => true,
    xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
    xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
);
$properties['mysql_array_driverOptions']= array();

/* sqlsrv */
$properties['sqlsrv_string_dsn_test']= 'sqlsrv:server=(local);database=revo_test';
$properties['sqlsrv_string_dsn_nodb']= 'sqlsrv:server=(local)';
$properties['sqlsrv_string_dsn_error']= 'sqlsrv:server=xyz;123';
$properties['sqlsrv_string_username']= '';
$properties['sqlsrv_string_password']= '';
$properties['sqlsrv_array_options']= array(
    xPDO::OPT_CACHE_PATH => $properties['cache_path'],
    xPDO::OPT_HYDRATE_FIELDS => true,
    xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
    xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
);
$properties['sqlsrv_array_driverOptions']= array(/*PDO::SQLSRV_ATTR_DIRECT_QUERY => false*/);

/* sqlite */
$properties['sqlite_string_dsn_test']= 'sqlite:/core/data/modx.db3';
$properties['sqlite_string_dsn_nodb']= 'sqlite:/core/data/modx.db3';
$properties['sqlite_string_dsn_error']= 'sqlite-err:nodb';
$properties['sqlite_string_username']= '';  /*NEXT DRIVER*/
$properties['sqlite_string_password']= '';  /*NEXT DRIVER*/
$properties['sqlite_array_options']= array(
    xPDO::OPT_CACHE_PATH => $properties['cache_path'],
    xPDO::OPT_HYDRATE_FIELDS => true,
    xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
    xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
);
$properties['sqlite_array_driverOptions']= array(/*NEXT DRIVER*/);


/* PHPUnit test config */
$properties['xpdo_driver']= 'mysql';
$properties['logLevel']= xPDO::LOG_LEVEL_INFO;
/* $properties['debug']= -1; */
