<?php
/**
 * @package modx
 * @subpackage registry.db.mysql
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/moddbregisterqueue.class.php');
class modDbRegisterQueue_mysql extends modDbRegisterQueue {}