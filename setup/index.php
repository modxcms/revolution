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
 * Instantiates the setup program.
 *
 * @package modx
 * @subpackage setup
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'provisioner' . DIRECTORY_SEPARATOR . 'bootstrap.php';

$modInstall = new modInstall();
if ($modInstall->getService('lexicon','modInstallLexicon')) {
    $modInstall->lexicon->load('default');
}
//$modInstall->findCore();
$modInstall->doPreloadChecks();
$requestClass = MODX_SETUP_INTERFACE_IS_CLI ? 'request.modInstallCLIRequest' : 'request.modInstallRequest';
$modInstall->getService('request',$requestClass);
echo $modInstall->request->handle();
exit();
