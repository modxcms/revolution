<?php
/**
 * MODX Revolution
 *
 * Copyright 2006-2014 by MODX, LLC.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package modx-test
 */
/**
 * Sample properties file for testing.
 *
 * @package modx-test
 */

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
$properties['mysql_array_options']= array(
    xPDO::OPT_HYDRATE_FIELDS => true,
    xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
    xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
);
$properties['mysql_array_driverOptions']= array();

/* sqlite */
$properties['sqlite_string_dsn_test']= 'sqlite:' . dirname(__FILE__) . 'test.sqlite3';
$properties['sqlite_string_dsn_nodb']= 'sqlite::memory:';
$properties['sqlite_string_dsn_error']= 'sqlite:db/';
$properties['sqlite_string_username']= '';
$properties['sqlite_string_password']= '';
$properties['sqlite_array_driverOptions']= array();
$properties['sqlite_array_options']= array(
    xPDO::OPT_CACHE_PATH => dirname(__FILE__) . '/cache/',
    xPDO::OPT_HYDRATE_FIELDS => true,
    xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
    xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
    xPDO::OPT_CONN_INIT => array(xPDO::OPT_CONN_MUTABLE => true),
    xPDO::OPT_CONNECTIONS => array(
        array(
            'dsn' => $properties['sqlite_string_dsn_test'],
            'username' => $properties['sqlite_string_username'],
            'password' => $properties['sqlite_string_password'],
            'options' => array(
                xPDO::OPT_CONN_MUTABLE => true,
            ),
            'driverOptions' => $properties['sqlite_array_driverOptions'],
        ),
    ),
);

/* sqlsrv */
$properties['sqlsrv_string_dsn_test']= 'sqlsrv:server=(local);database=revo_test';
$properties['sqlsrv_string_dsn_nodb']= 'sqlsrv:server=(local)';
$properties['sqlsrv_string_dsn_error']= 'sqlsrv:server=xyz;123';
$properties['sqlsrv_string_username']= '';
$properties['sqlsrv_string_password']= '';
$properties['sqlsrv_array_options']= array(
    xPDO::OPT_HYDRATE_FIELDS => true,
    xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
    xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
);
$properties['sqlsrv_array_driverOptions']= array(/*PDO::SQLSRV_ATTR_DIRECT_QUERY => false*/);

/* PHPUnit test config */
$properties['xpdo_driver']= ((getenv('TEST_DRIVER') == null) ? 'sqlite' : getenv('TEST_DRIVER'));
$properties['logTarget']= array(
    'target' => 'file',
    'options' => array(
        'filename' => "unit_test_{$properties['runtime']}.log",
        'filepath' => dirname(__FILE__) . '/'
    )
);
$properties['logLevel']= modX::LOG_LEVEL_INFO;
$properties['context'] = 'web';
$properties['debug'] = false;
