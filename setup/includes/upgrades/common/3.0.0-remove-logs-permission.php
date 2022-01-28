<?php

/**
 * Common upgrade script to remove the outdated logs permission
 *
 * @var modX $modx
 * @package setup
 */

use MODX\Revolution\modAccessPermission;

/** @var modAccessPermission $logsPermission */
$logsPermission = $modx->getObject(modAccessPermission::class, ['name' => 'logs']);

if ($logsPermission) {
    $logsPermission->remove();
}
