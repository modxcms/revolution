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
 */
if ($install->isLocked()) {
    return $parser->render('locked.tpl');
}

if (!empty($_POST['proceed'])) {
    unset($_POST['proceed']);
    $install->settings->store($_POST);
    $this->proceed('install');
}

$mode = $install->settings->get('installmode');
$results= $install->test($mode);

$failed = false;
foreach ($results as $item) {
    if (isset($item['class']) && $item['class'] === 'testFailed') {
        $failed = true;
        break;
    }
}

if ($mode === modInstall::MODE_UPGRADE_REVO) {
    $back = 'options';
} else {
    $back = MODX_SETUP_KEY === '@traditional@' ? 'database' : 'contexts';
}

$parser->set('test', $results);
$parser->set('failed', $failed);
$parser->set('testClass', $failed ? 'error' : 'success');
$parser->set('back', $back);

return $parser->render('summary.tpl');
