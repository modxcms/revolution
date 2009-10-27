<?php
/**
 * @package modx
 * @subpackage mysql
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/modaccesspermission.class.php');
class modAccessPermission_mysql extends modAccessPermission {}