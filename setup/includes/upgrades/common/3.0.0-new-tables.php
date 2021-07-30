<?php

/**
 * Add new tables introduced in 3.0
 *
 * @var modX $modx
 *
 * @package setup
 */

$manager = $modx->getManager();

$manager->createObjectContainer(\MODX\Revolution\modDeprecatedMethod::class);
$manager->createObjectContainer(\MODX\Revolution\modDeprecatedCall::class);
