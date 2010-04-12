<?php
/**
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
    $this->parser->assign('errors',$errors);
}

return $this->parser->fetch('complete.tpl');