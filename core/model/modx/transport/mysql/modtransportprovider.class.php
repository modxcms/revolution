<?php
/**
 * @package modx
 * @subpackage transport.mysql
 */
require_once (strtr(realpath(dirname(__DIR__)), '\\', '/') . '/modtransportprovider.class.php');
/**
 * @package modx
 * @subpackage mysql
 */
class modTransportProvider_mysql extends modTransportProvider {}