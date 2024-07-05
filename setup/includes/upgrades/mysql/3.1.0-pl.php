<?php
/**
 * Specific upgrades for Revolution 3.1.0-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

/* run upgrades common to all db platforms */
include dirname(__DIR__) . '/common/3.1.0-remove-deprecated-resource-fields.php';
include dirname(__DIR__) . '/common/3.1.0-modify-usergrouprole-authority-index.php';
