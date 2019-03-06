<?php
/**
 * Specific upgrades for Revolution 3.0.0-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

/* run upgrades common to all db platforms */
include dirname(__DIR__) . '/common/3.0.0-dashboard-widgets.php';
include dirname(__DIR__) . '/common/3.0.0-remove-copy-to-clipboard.php';
include dirname(__DIR__) . '/common/3.0.0-cleanup-system-settings.php';
include dirname(__DIR__) . '/common/3.0.0-remove-tv-eval-system-setting.php';
include dirname(__DIR__) . '/common/3.0.0-remove-upload-flash-system-setting.php';
include dirname(__DIR__) . '/common/3.0.0-content-type-icon.php';
include dirname(__DIR__) . '/common/3.0.0-update-upload_files-upload_images.php';
include dirname(__DIR__) . '/common/3.0.0-trim-gender-field-size.php';
