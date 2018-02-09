<?php
/**
 * @var modInstall $install
 * @var modInstallParser $parser
 * @var modInstallRequest $this
 * @package setup
 */
$lang = $install->settings->get('language');

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

/* Set language after cleanup to display page in current language. */
$install->settings->set('language', $lang);

return $parser->render('complete.tpl');
