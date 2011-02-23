<?php
/*
 * Define the MODX path constants necessary for building a distribution from a build image
 *
 * NOTE: do not change MODX_CORE_PATH for distributions as it must run against the buildImage
 * NOTE: do not add other MODX_ constants here as the image uses the default paths
 */
define('MODX_CORE_PATH', dirname(__FILE__) . '/image/' . $buildImage . '/core/');
define('MODX_CONFIG_KEY', 'config');

/* define the connection variables */
define('XPDO_DSN', 'mysql:host=localhost;dbname=revo_test;charset=utf8');
define('XPDO_DB_USER', '');
define('XPDO_DB_PASS', '');
define('XPDO_TABLE_PREFIX', 'modx_');
