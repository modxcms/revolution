<?php
/**
 * @package setup
 */
if (!empty($_POST['proceed'])) {
    $install->settings->store($_POST);
    /* validate database settings */
    $errors= $install->cleanup($_POST);

    if (!empty ($errors)) {
        $this->parser->assign('errors',implode('', $errors));
    } else {
        $this->proceed('login');
    }
}


$errors= $install->verify();
if (!empty ($errors)) {
    $this->parser->assign('errors',implode('', $errors));
}

return $this->parser->fetch('complete.tpl');