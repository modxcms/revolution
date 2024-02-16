<?php

/**
 * Database changes for 3.0.5
 *
 * @var modX $modx
 * @package setup
 */

$manager = $modx->getManager();

$manager->alterField(\MODX\Revolution\modManagerLog::class, 'occurred');
