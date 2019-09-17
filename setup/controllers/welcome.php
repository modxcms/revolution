<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @var modInstall $install
 * @var modInstallParser $parser
 * @var modInstallRequest $this
 *
 * @package setup
 */
if ($install->isLocked()) {
    return $parser->render('locked.tpl');
}

$install->settings->check();
$proceed = false;
$writable = is_writable(MODX_SETUP_PATH . 'includes/config.core.php');
$writableError = false;
$config_key = isset($_POST['config_key']) && !empty($_POST['config_key'])
    ? str_replace(array('{','}',"'",'"','\$'), '', $_POST['config_key'])
    : MODX_CONFIG_KEY;
if (!empty($_POST['proceed'])) {
    if ($config_key !== MODX_CONFIG_KEY) {
        if ($writable) {
            $content = file_get_contents(MODX_SETUP_PATH . 'includes/config.core.php');
            $pattern = "/define\s*\(\s*'MODX_CONFIG_KEY'\s*,.*\);/";
            $replacement = "define ('MODX_CONFIG_KEY', '{$config_key}');";
            $content = preg_replace($pattern, $replacement, $content);
            file_put_contents(MODX_SETUP_PATH . 'includes/config.core.php', $content);
            $proceed = true;
        } else {
            $writableError = true;
        }
    } else {
        $proceed = true;
    }
    if ($proceed) {
        $this->proceed('options');
    }
}

$parser->set('config_key', $config_key);
$parser->set('writableError', $writableError);

return $parser->render('welcome.tpl');
