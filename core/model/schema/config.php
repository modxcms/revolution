<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$properties = [];

require_once (dirname(dirname(__DIR__)) . '/vendor/autoload.php');
require_once (dirname(dirname(dirname(__DIR__))) . '/config.core.php');
require_once (dirname(dirname(dirname(__DIR__))) . '/_build/build.properties.php');

return [
    'mysql_array_options' => [
        \xPDO\xPDO::OPT_CACHE_PATH => __DIR__ . '/cache/',
        \xPDO\xPDO::OPT_HYDRATE_FIELDS => true,
        \xPDO\xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
        \xPDO\xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
        \xPDO\xPDO::OPT_CONN_INIT => [\xPDO\xPDO::OPT_CONN_MUTABLE => true],
        \xPDO\xPDO::OPT_CONNECTIONS => [
            [
                'dsn' => $properties['mysql_string_dsn_test'],
                'username' => $properties['mysql_string_username'],
                'password' => $properties['mysql_string_password'],
                'options' => [
                    \xPDO\xPDO::OPT_CONN_MUTABLE => true,
                ],
                'driverOptions' => [],
            ],
        ],
        'log_target' => 'ECHO',
        'log_level' => \xPDO\xPDO::LOG_LEVEL_WARN,
    ],
];
