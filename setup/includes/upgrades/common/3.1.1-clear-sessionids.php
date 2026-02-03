<?php

/**
 * Removes data from modUserProfile.sessionid field
 *
 * @var modX $modx
 * @package setup
 */

$modx->updateCollection(\MODX\Revolution\modUserProfile::class, ['sessionid' => '']);
