<?php
/* define the MODX path constants necessary for core installation */
define('MODX_CORE_PATH', dirname(dirname(__FILE__)) . '/core/');
define('MODX_CONFIG_KEY', 'config');


/* define the connection variables */
define('XPDO_DSN', 'sqlite:/core/data/modx.db3');
define('XPDO_DB_USER', ''); /*NEXT DRIVER*/
define('XPDO_DB_PASS', ''); /*NEXT DRIVER*/
define('XPDO_TABLE_PREFIX', 'modx_');
