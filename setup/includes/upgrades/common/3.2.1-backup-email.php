<?php

/**
 * Adds backup_email column to user_attributes for passwordless login fallback
 *
 * @var modX $modx
 * @package setup
 * @subpackage upgrades
 */

use MODX\Revolution\modUserProfile;

$table = $modx->getTableName(modUserProfile::class);
$sql = "ALTER TABLE {$modx->escape($table)} ADD COLUMN `backup_email` VARCHAR(100) NULL DEFAULT ''";
$modx->exec($sql);
