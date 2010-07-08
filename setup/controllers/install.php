<?php
/**
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
$this->parser->assign('results', $results);

$failed= false;
foreach ($results as $item) {
    if ($item['class'] === 'failed') {
        $failed= true;
        break;
    }
}
$this->parser->assign('failed', $failed);
$this->parser->assign('itemClass', $failed ? 'error' : '');
return $this->parser->fetch('install.tpl');