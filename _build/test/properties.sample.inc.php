<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
*/

/**
 * Sample properties file for testing.
 *
 * @package modx-test
 */

use xPDO\xPDO;

/* define some properties */
$properties['runtime'] = strftime("%Y%m%dT%H%M%S");
$properties['config_key'] = 'test';

/* driver-specific connection properties */
/* mysql */
$properties['mysql_string_dsn_test']= 'mysql:host=localhost;dbname=revo_test;charset=utf8';
$properties['mysql_string_dsn_nodb']= 'mysql:host=localhost;charset=utf8';
$properties['mysql_string_dsn_error']= 'mysql:host= nonesuchhost;dbname=nonesuchdb';
$properties['mysql_string_username']= '';
$properties['mysql_string_password']= '';
$properties['mysql_array_options']= [
    xPDO::OPT_HYDRATE_FIELDS => true,
    xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
    xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
];
$properties['mysql_array_driverOptions']= [];

/* sqlsrv */
$properties['sqlsrv_string_dsn_test']= 'sqlsrv:server=(local);database=revo_test';
$properties['sqlsrv_string_dsn_nodb']= 'sqlsrv:server=(local)';
$properties['sqlsrv_string_dsn_error']= 'sqlsrv:server=xyz;123';
$properties['sqlsrv_string_username']= '';
$properties['sqlsrv_string_password']= '';
$properties['sqlsrv_array_options']= [
    xPDO::OPT_HYDRATE_FIELDS => true,
    xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
    xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
];
$properties['sqlsrv_array_driverOptions']= [/*PDO::SQLSRV_ATTR_DIRECT_QUERY => false*/];

/* PHPUnit test config */
$properties['xpdo_driver']= 'mysql';
$properties['logTarget']= [
    'target' => 'file',
    'options' => [
        'filename' => "unit_test_{$properties['runtime']}.log",
        'filepath' => dirname(__FILE__) . '/'
    ]
];
$properties['logLevel']= xPDO::LOG_LEVEL_INFO;
$properties['context'] = 'web';
$properties['debug'] = false;
