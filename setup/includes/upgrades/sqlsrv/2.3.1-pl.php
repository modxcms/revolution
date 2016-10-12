<?php
/**
 * Specific upgrades for Revolution 2.3.1-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

/* run upgrades common to all db platforms */
include dirname(__DIR__) . '/common/2.3.1-oninitculture.php';
include dirname(__DIR__) . '/common/2.3.1-feed_modx_security.php';
include dirname(__DIR__) . '/common/2.3.1-base_help_url.php';
