<?php
/*1
 * Specific upgrades for Revolution 2.5.0-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

/* run upgrades common to all db platforms */
include dirname(dirname(__FILE__)) . '/common/2.5-user-createdon.php';
include dirname(dirname(__FILE__)) . '/common/2.5-cleanup-script.php';
