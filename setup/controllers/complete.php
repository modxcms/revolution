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
 * @package setup
 */

if (!empty($_POST['proceed'])) {
    $install->settings->store($_POST);
    $this->proceed('login');
}

$errors= $install->verify();
$cleanupErrors = $install->cleanup();
if (is_array($cleanupErrors) && !empty($cleanupErrors)) {
    $errors= array_merge($errors,$cleanupErrors);
}

if (!empty ($errors)) {
    $parser->set('errors',$errors);
}

/* check delete setup/ if not using git version [#2512] */
if (!defined('MODX_SETUP_KEY')) { define('MODX_SETUP_KEY','@git@'); }
$distro = trim(MODX_SETUP_KEY,'@');
$parser->set('cleanup',$distro != 'git' ? true : false);

return $parser->render('complete.tpl');
