<?php
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
$install->getService('runner','runner.modInstallRunnerWeb');
$results = array();
if ($install->runner) {
    $success = $install->runner->run($mode);
    $results = $install->runner->getResults();

    $failed= false;
    foreach ($results as $item) {
        if ($item['class'] === 'failed') {
            $failed= true;
            break;
        }
    }
} else {
    $failed = true;
}
$parser->set('failed', $failed);
$parser->set('itemClass', $failed ? 'error' : '');
$parser->set('results',$results);
return $parser->render('install.tpl');