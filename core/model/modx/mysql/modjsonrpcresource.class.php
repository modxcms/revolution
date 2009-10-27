<?php
/**
 * @package modx
 * @subpackage mysql
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/modjsonrpcresource.class.php');
class modJSONRPCResource_mysql extends modJSONRPCResource {}