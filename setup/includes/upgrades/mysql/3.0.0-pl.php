<?php
/**
 * Specific upgrades for Revolution 3.0.0-pl
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

/* run upgrades common to all db platforms */
include dirname(__DIR__) . '/common/3.0.0-cleanup-files.php';
include dirname(__DIR__) . '/common/3.0.0-dashboard-widgets.php';
include dirname(__DIR__) . '/common/3.0.0-new-tables.php';
include dirname(__DIR__) . '/common/3.0.0-remove-copy-to-clipboard.php';
include dirname(__DIR__) . '/common/3.0.0-cleanup-system-settings.php';
include dirname(__DIR__) . '/common/3.0.0-content-type-icon.php';
include dirname(__DIR__) . '/common/3.0.0-update-xtypes-system-settings.php';
include dirname(__DIR__) . '/common/3.0.0-trim-gender-field-size.php';
include dirname(__DIR__) . '/common/3.0.0-update-legacy-class-references.php';
include dirname(__DIR__) . '/common/3.0.0-update-menu-entries.php';
include dirname(__DIR__) . '/common/3.0.0-update-menu-main.php';
include dirname(__DIR__) . '/common/3.0.0-update-sys-setting_default_media_source.php';
include dirname(__DIR__) . '/common/3.0.0-update-sys-setting_upload_files.php';
include dirname(__DIR__) . '/common/3.0.0-update-tvs-params.php';
include dirname(__DIR__) . '/common/3.0.0-update-tvs-output-params.php';
include dirname(__DIR__) . '/common/3.0.0-update-tvs-list-legacy.php';
include dirname(__DIR__) . '/common/3.0.0-update-sys-setting_base_help_url.php';
include dirname(__DIR__) . '/common/3.0.0-update-principal_targets.php';
include dirname(__DIR__) . '/common/3.0.0-update-sys-setting_welcome_screen_url.php';
include dirname(__DIR__) . '/common/3.0.0-policy-description.php';
include dirname(__DIR__) . '/common/3.0.0-policy-template-group-description.php';
include dirname(__DIR__) . '/common/3.0.0-policy-template-description.php';
include dirname(__DIR__) . '/common/3.0.0-non-index-field-length.php';
include dirname(__DIR__) . '/common/3.0.0-template-preview.php';
include dirname(__DIR__) . '/common/3.0.0-remove-content-type-field.php';
include dirname(__DIR__) . '/common/3.0.0-remove-logs-permission.php';
include dirname(__DIR__) . '/common/3.0.0-update-smtp-system-settings.php';
