<?php
/**
 * Specific upgrades for Revolution 3.0.2-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

/* run upgrades common to all db platforms */
include dirname(__DIR__) . '/common/3.0.2-remove-deprecated-resource-fields.php';
