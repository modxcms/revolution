<?php
/**
 * Specific upgrades for Revolution 2.4.0-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

/* run upgrades common to all db platforms */
include dirname(__DIR__) . '/common/2.4-package-provider.php';
include dirname(__DIR__) . '/common/2.4-categories-rank.php';
include dirname(__DIR__) . '/common/2.4-namespace-access.php';
include dirname(__DIR__) . '/common/2.4-principal-index.php';
include dirname(__DIR__) . '/common/2.4-tmplvar-index.php';
include dirname(__DIR__) . '/common/2.4-manager-index.php';
include dirname(__DIR__) . '/common/2.4-user-country-iso.php';
