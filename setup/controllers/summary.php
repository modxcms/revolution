<?php
/**
 * @package setup
 */
if (!empty($_POST['proceed'])) {
    unset($_POST['proceed']);
    $install->settings->store($_POST);
    $this->proceed('install');
}

$mode = $install->settings->get('installmode');
$results= $install->test($mode);
$this->parser->assign('test', $results);

$failed= false;
foreach ($results as $item) {
    if ($item['class'] === 'testFailed') {
        $failed= true;
        break;
    }
}
$this->parser->assign('failed', $failed);
$this->parser->assign('testClass', $failed ? 'error' : 'success');
$this->parser->assign('back',MODX_SETUP_KEY == '@traditional' ? 'database' : 'contexts');
return $this->parser->fetch('summary.tpl');
