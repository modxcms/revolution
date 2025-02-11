<?php
/**
 * Specific upgrades for Revolution 3.1.1-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

/* run upgrades common to all db platforms */
include dirname(__DIR__) . '/common/3.1.1-clear-sessionids.php';
