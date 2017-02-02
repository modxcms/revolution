<?php
/**
 * @package modx
 * @subpackage registry.db.mysql
 */
require_once (strtr(realpath(dirname(__DIR__)), '\\', '/') . '/moddbregisterqueue.class.php');
/**
 * @package modx
 * @subpackage mysql
 */
class modDbRegisterQueue_mysql extends modDbRegisterQueue {}