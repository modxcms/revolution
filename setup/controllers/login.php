<?php
/**
 * Handle any final cleanups and redirect to login screen
 *
 * @package setup
 */
if ($install->settings->get('cleanup')) {
    $install->removeSetupDirectory();
}
$managerUrl= $install->getManagerLoginUrl();
header('Location: ' . $managerUrl);
exit();