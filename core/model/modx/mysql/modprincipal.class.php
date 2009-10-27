<?php
/**
 * @package modx
 * @subpackage mysql
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/modprincipal.class.php');
class modPrincipal_mysql extends modPrincipal {}