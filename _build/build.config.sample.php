<?php
// define the MODX path constants necessary for core installation
define('MODX_CORE_PATH', dirname(dirname(__FILE__)) . '/core/');
define('MODX_BASE_PATH', dirname(dirname(__FILE__)) . '/');
define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'manager/');
define('MODX_CONNECTORS_PATH', MODX_BASE_PATH . 'connectors/');
define('MODX_ASSETS_PATH', MODX_BASE_PATH . 'assets/');

// define the connection variables
define('XPDO_DSN', 'mysql:host=localhost;dbname=modx;charset=utf8');
define('XPDO_DB_USER', 'root');
define('XPDO_DB_PASS', '');
define('XPDO_TABLE_PREFIX', 'modx_');
?>