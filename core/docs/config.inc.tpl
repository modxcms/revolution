<?php
/**
 *  MODX Configuration file
 */

return [
    'last_install_time' => {last_install_time},
    'id' => '{site_id}',
    'https_port' => '{https_port}',
    'uuid' => '{uuid}',
    'http_host' => '{http_host}',
    'disable_cache' => {cache_disabled},
    'db' => [
        'type' => '{database_type}',
        'server' => '{database_server}',
        'user' => '{database_user}',
        'password' => '{database_password}',
        'connection_charset' => '{database_connection_charset}',
        'database' => '{dbase}',
        'table_prefix' => '{table_prefix}',
        'dns' => '{database_dsn}',
        'config_options' => {config_options},
        'driver_options' => {driver_options},
    ],
    'paths' => [
        'base' => '{web_path}',
        'core' => '{core_path}',
        'processors' => '{processors_path}',
        'connectors' => '{connectors_path}',
        'manager' => '{mgr_path}',
        'assets' => '{assets_path}',
    ],
    'urls' => [
        'base' => '{web_url}',
        'connectors' => '{connectors_url}',
        'manager' => '{mgr_url}',
        'assets' => '{assets_url}',
    ],
    'log_levels' => [
        'fatal' => 0,
        'error' => 1,
        'warn' => 2,
        'info' => 3,
        'debug' => 4,
    ]
];