<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

define('MODX_SETUP_INTERFACE_IS_CLI', (PHP_SAPI === 'cli'));
define('MODX_SETUP_PHP_VERSION', phpversion());

$setupPath= strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/';
define('MODX_SETUP_PATH', $setupPath);
$installPath= strtr(realpath(dirname(dirname(__DIR__))), '\\', '/') . '/';
define('MODX_INSTALL_PATH', $installPath);

if (!MODX_SETUP_INTERFACE_IS_CLI) {
    $https = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : false;
    $installBaseUrl= (!$https || strtolower($https) != 'on') ? 'http://' : 'https://';
    $installBaseUrl .= $_SERVER['HTTP_HOST'];
    if (isset($_SERVER['SERVER_PORT']) && (string)$_SERVER['SERVER_PORT'] != '' && $_SERVER['SERVER_PORT'] != 80) $installBaseUrl= str_replace(':' . $_SERVER['SERVER_PORT'], '', $installBaseUrl);
    $installBaseUrl .= ($_SERVER['SERVER_PORT'] == 80 || ($https !== false || strtolower($https) == 'on')) ? '' : ':' . $_SERVER['SERVER_PORT'];
    $installBaseUrl .= $_SERVER['SCRIPT_NAME'];
    $installBaseUrl = htmlspecialchars($installBaseUrl, ENT_QUOTES, 'utf-8');
    define('MODX_SETUP_URL', $installBaseUrl);
} else {
    define('MODX_SETUP_URL','/');
}

/*
 * Start validating MODX requirements
 */
$unsatisfiedRequirementsErrors = [];

/* Load and check PHP and installed extensions */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'requirements.php';

$phpVersionSatisfiesRequirement = version_compare(MODX_SETUP_PHP_VERSION, MODX_MINIMUM_REQUIRED_PHP_VERSION, '>=');
if (!$phpVersionSatisfiesRequirement) {
    $unsatisfiedRequirementsErrors[] = [
        'title' => 'Wrong PHP Version!',
        'description' => sprintf('You\'re using PHP version %s, and MODX requires version %s or higher.', MODX_SETUP_PHP_VERSION, MODX_MINIMUM_REQUIRED_PHP_VERSION),
    ];
}

$unsatisfiedExtensionRequirements = array_filter(MODX_REQUIRED_EXTENSIONS, function($extensionToValidate) {
    $extensionVersion = phpversion($extensionToValidate);
    $extensionIsInstalled = ($extensionVersion !== false);
    return !$extensionIsInstalled;
});
if (!empty($unsatisfiedExtensionRequirements)) {
    foreach ($unsatisfiedExtensionRequirements as $unsatisfiedExtensionRequirement) {
        $unsatisfiedRequirementsErrors[] = [
            'title' => sprintf('MODX requires the PHP %s extension', $unsatisfiedExtensionRequirement),
            'description' => sprintf('You\'re PHP configuration at version %s does not appear to have this extension enabled.', MODX_SETUP_PHP_VERSION),
        ];
    }
}

/* Validate functions and settings */
$sessionsWorking = (session_start() && session_status() === PHP_SESSION_ACTIVE);
if (!$sessionsWorking) {
    $unsatisfiedRequirementsErrors[] = [
        'description' => 'Make sure your PHP session configuration is valid and working.',
    ];
}

$phptz = @ini_get('date.timezone');
if (empty($phptz)) {
    date_default_timezone_set('UTC');
}
$defaultTimezoneSet = date_default_timezone_get();
if (!$defaultTimezoneSet) {
    $unsatisfiedRequirementsErrors[] = [
        'description' => 'You must set the date.timezone setting in your php.ini (or have at least UTC in the list of supported timezones). Please do set it to a proper timezone before proceeding.',
    ];
}

/* Validate critical files */
$setupCoreConfigFileUsable = is_readable(MODX_SETUP_PATH . 'includes/config.core.php');
if (!$setupCoreConfigFileUsable) {
    $unsatisfiedRequirementsErrors[] = [
        'description' => 'Make sure you have uploaded all of the setup/ files; your setup/includes/config.core.php file is missing.',
    ];
}
$modInstallClassFileUsable = is_readable(MODX_SETUP_PATH . 'includes/modinstall.class.php');
if (!$modInstallClassFileUsable) {
    $unsatisfiedRequirementsErrors[] = [
        'description' => 'Make sure you have uploaded all of the setup/ files; your setup/includes/modinstall.class.php file is missing.',
    ];
}

/* Prevent setup if not all requirements are satisfied */
if (!empty($unsatisfiedRequirementsErrors)) {
    if (!MODX_SETUP_INTERFACE_IS_CLI) {
        require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'templates/requirements.php';
        exit();
    }

    echo 'MODX Setup cannot continue.' . PHP_EOL;
    foreach ($unsatisfiedRequirementsErrors as $unsatisfiedRequirementError) {
        echo sprintf('%s %s' . PHP_EOL, $unsatisfiedRequirementError['title'] ?? '', $unsatisfiedRequirementError['description'] ?? '');
    }

    exit();
}
/*
 * End validating MODX requirements
 */

if (MODX_SETUP_INTERFACE_IS_CLI) {
    foreach ($argv as $idx => $argument) {
        $p = explode('=',ltrim($argument,'--'));
        if (isset($p[1])) {
            $_REQUEST[$p[0]] = $p[1];
        }
    }
    if (!empty($_REQUEST['core_path']) && is_dir($_REQUEST['core_path'])) {
        define('MODX_CORE_PATH',$_REQUEST['core_path']);
    }
    if (!empty($_REQUEST['config_key'])) {
        $_REQUEST['config_key'] = str_replace(['{','}',"'",'"','\$'], '', $_REQUEST['config_key']);
        define('MODX_CONFIG_KEY',$_REQUEST['config_key']);
    }
}

include MODX_SETUP_PATH . 'includes/config.core.php';
include MODX_SETUP_PATH . 'includes/modinstall.class.php';
