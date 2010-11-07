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

/* check delete setup/ if not using git version [#2512] */
if (!defined(MODX_SETUP_KEY)) { define('MODX_SETUP_KEY','@git@'); }
$distro = trim(MODX_SETUP_KEY,'@');
$this->parser->assign('cleanup',$distro != 'git' ? true : false);

return $this->parser->fetch('complete.tpl');
