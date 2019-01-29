<?php
if (!defined('MODX_CORE_PATH')) {
    if (file_exists(MODX_INSTALL_PATH . 'config.core.php')) {
        require_once(MODX_INSTALL_PATH . 'config.core.php');
    } else {
        define('MODX_CORE_PATH', MODX_INSTALL_PATH . 'core/');
    }
}
if (!defined('MODX_CONFIG_KEY')) define ('MODX_CONFIG_KEY', 'config');
define ('MODX_SETUP_KEY', '@git@');
