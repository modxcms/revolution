<?php
/**
 * Specific upgrades for Revolution 2.5.0-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

/* run upgrades common to all db platforms */
include dirname(__DIR__) . '/common/2.5-user-createdon.php';
include dirname(__DIR__) . '/common/2.5-cleanup-script.php';
