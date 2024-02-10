<?php

/**
 * Database changes for 3.1
 *
 * @var modX $modx
 * @package setup
 */

$manager = $modx->getManager();

$manager->alterField(\MODX\Revolution\modManagerLog::class, 'occurred');
