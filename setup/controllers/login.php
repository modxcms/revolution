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
 * Handle any final cleanups and redirect to login screen
 *
 * @var modInstall $install
 * @var modInstallParser $parser
 * @var modInstallRequest $this
 *
 * @package setup
 */
if ($install->settings->get('cleanup')) {
    $install->removeSetupDirectory();
}
$install->settings->erase();
$managerUrl= $install->getManagerLoginUrl();
header('Location: ' . $managerUrl);
exit();
