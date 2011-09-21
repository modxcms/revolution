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
$results= $install->execute($mode);
$parser->set('results', $results);

$failed= false;
foreach ($results as $item) {
    if ($item['class'] === 'failed') {
        $failed= true;
        break;
    }
}
$parser->set('failed', $failed);
$parser->set('itemClass', $failed ? 'error' : '');
$parser->set('results',$results);
return $parser->fetch('install.tpl');