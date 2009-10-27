<?php
/**
 * @package modx
 * @subpackage mysql
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../modeventlog.class.php');
class modEventLog_mysql extends modEventLog {}