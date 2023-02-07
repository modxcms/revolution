<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/**
 * @var modX $modx
 */

/* modify legacy modSessionHandler references in system settings */
$class = \MODX\Revolution\modSystemSetting::class;
$table = $modx->getTableName($class);

$modx->updateCollection($class, ['value' => \MODX\Revolution\modSessionHandler::class], [
    'key' => 'session_handler_class',
    'value' => 'modSessionHandler'
]);

/* modify legacy modSessionHandler references in context settings */
$class = \MODX\Revolution\modContextSetting::class;
$table = $modx->getTableName($class);

$modx->updateCollection($class, ['value' => \MODX\Revolution\modSessionHandler::class], [
    'key' => 'session_handler_class',
    'value' => 'modSessionHandler'
]);
