<?php
/**
 * @package modx
 * @subpackage registry.db.mysql
 */
require_once (strtr(realpath(dirname(__DIR__)), '\\', '/') . '/moddbregistertopic.class.php');
/**
 * @package modx
 * @subpackage mysql
 */
class modDbRegisterTopic_mysql extends modDbRegisterTopic {}