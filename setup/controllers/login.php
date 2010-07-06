<?php
/**
 * Handle any final cleanups and redirect to login screen
 *
 * @package setup
 */
if ($install->settings->get('cleanup')) {
    $install->removeSetupDirectory();
    $install->settings->erase();
}
$managerUrl= $install->getManagerLoginUrl();
header('Location: ' . $managerUrl);
exit();