<?php
/**
 * Specific upgrades for Revolution 2.7.0-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

/* run upgrades common to all db platforms */
include dirname(dirname(__FILE__)) . '/common/2.7-alias-visible.php';
include dirname(dirname(__FILE__)) . '/common/2.7-description-text.php';
include dirname(dirname(__FILE__)) . '/common/2.7-browser-tree-hide-files.php';
include dirname(dirname(__FILE__)) . '/common/2.7-remove-cache-disabled.php';
include dirname(dirname(__FILE__)) . '/common/2.7-native-password-hash.php';
