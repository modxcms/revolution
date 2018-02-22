<?php

/**
 * CLI install script for MODX
 *
 * Run and answer the questions. Also you can pre-define values:
 * php www/setup/install.php --database=my_db --database_user=db_user_12 --language=ru
 */

if (PHP_SAPI != 'cli') {
    exit('This file should be run through the command line interface!');
}
$path = dirname(__FILE__) . '/';
$config = $path . 'config.xml';
$mode = 'new';

$variables = [
    'database_type' => 'mysql',
    'database_server' => [
        'prompt' => 'Database server',
        'default' => '127.0.0.1',
    ],
    'database' => [
        'prompt' => 'Database',
        'default' => '',
    ],
    'database_user' => [
        'prompt' => 'Database user',
        'default' => '',
    ],
    'database_password' => [
        'prompt' => 'Database password',
        'default' => '',
    ],
    'database_connection_charset' => 'utf8',
    'database_charset' => 'utf8',
    'database_collation' => 'utf8_general_ci',
    'table_prefix' => [
        'prompt' => 'Table prefix',
        'default' => 'modx_',
    ],
    'https_port' => 443,
    'http_host' => [
        'prompt' => 'Http host',
        'default' => '',
    ],
    'cache_disabled' => 0,
    'inplace' => 0,
    'unpacked' => 0,
    'language' => [
        'prompt' => 'Language',
        'default' => 'en',
    ],
    'cmsadmin' => [
        'prompt' => 'Admin login',
        'default' => 'admin',
    ],
    'cmspassword' => [
        'prompt' => 'Admin password',
        'default' => '',
    ],
    'cmsadminemail' => [
        'prompt' => 'Admin email',
        'default' => '',
    ],
    'core_path' => [
        'prompt' => 'Path to core',
        'default' => dirname($path) . '/core/',
    ],
    'context_mgr_path' => [
        'prompt' => 'Path to manager',
        'default' => dirname($path) . '/manager/',
    ],
    'context_mgr_url' => [
        'prompt' => 'Manager url',
        'default' => '/manager/',
    ],
    'context_connectors_path' => [
        'prompt' => 'Path to connectors',
        'default' => dirname($path) . '/connectors/',
    ],
    'context_connectors_url' => [
        'prompt' => 'Url to connectors',
        'default' => '/connectors/',
    ],
    'context_web_path' => dirname($path) . '/',
    'context_web_url' => '/',
    'remove_setup_directory' => 1,
];

// Parse CLI arguments
$args = array_slice($argv, 1);
$cli_variables = [];
foreach ($args as $arg) {
    $tmp = array_map('trim', explode('=', $arg));
    if (count($tmp) === 2) {
        $k = ltrim($tmp[0], '-');
        if (isset($variables[$k])) {
            $cli_variables[$k] = $tmp[1];
        } elseif ($k == 'mode' && in_array($tmp[1], ['new', 'upgrade', 'advanced'])) {
            $mode = $tmp[1];
        }
    }
}

// If it is upgrade - parse old variables
$old_variables = [];
if (file_exists($config)) {
    $use = ($mode == 'upgrade')
        ? 'Y'
        : readline("There is an old config found at {$config}. Do you want to load this values? (Y): ");
    if (empty($use) || strtolower($use) == 'y') {
        $tmp = simplexml_load_file($config);
        $tmp = @json_decode(json_encode($tmp), true);
        if (is_array($tmp)) {
            $old_variables = $tmp;
        }
    }
}

// Merge everything
$variables = array_merge($variables, $old_variables, $cli_variables);

// Process config
$data = [];
foreach ($variables as $key => $params) {
    if (!is_array($params)) {
        $data[$key] = $params;
    } elseif (!empty($params['prompt'])) {
        $prompt = $params['prompt'];
        $res = '';
        if (!empty($params['default'])) {
            $prompt .= " ({$params['default']})";
            if (!$res = readline($prompt . ': ')) {
                $res = $params['default'];
            }
        } else {
            while (!$res = readline($prompt . ': ')) {
                echo $prompt . " is required!\n";
            }
            readline_add_history(trim($res));
        }
        $data[$key] = trim($res);
    }
    if (strpos($key, '_path') !== false || strpos($key, '_url') !== false) {
        $data[$key] = preg_replace('#/+#', '/', ('/' . trim($data[$key], '/') . '/'));
    }
}

// Check directories
$dirs = [
    'core_path' => '/core/',
    'context_mgr_path' => '/manager/',
    'context_connectors_path' => '/connectors/',
];
foreach ($dirs as $key => $value) {
    $target = dirname($path) . $value;
    if (file_exists($target) && $data[$key] != $target) {
        shell_exec("rm -rf {$data[$key]}");
        shell_exec("mv {$target} {$data[$key]}");
    }
}

// Generate config file
$xml = new SimpleXMLElement('<modx/>');
foreach ($data as $key => $value) {
    $xml->addChild($key, $value);
}
file_put_contents($config, $xml->asXML());

// Run install!
echo shell_exec("php {$path}index.php --installmode={$mode} --core_path={$data['core_path']} --config={$config}");