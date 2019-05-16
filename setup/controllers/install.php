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
$install->settings->check();
if (!empty($_POST['proceed'])) {
    unset($_POST['proceed']);
    $install->settings->store($_POST);
    $this->proceed('complete');
}

$mode = $install->settings->get('installmode');
$install->getService('runner', 'runner.modInstallRunnerWeb');

$results = [];

if ($install->runner) {
    $success = $install->runner->run($mode);
    $results = $install->runner->getResults();
    
    usort($results, function ($a, $b) {
        return $a['class'] < $b['class'];
    });
    
    $failed = count($results) && array_reverse($results)[0]['class'] === 'failed';
} else {
    $failed = true;
}
$parser->set('failed', $failed);
$parser->set('itemClass', $failed ? 'error' : '');
$parser->set('results', $results);

return $parser->render('install.tpl');
