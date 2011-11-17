<?php
/**
 * Handle any final cleanups and redirect to login screen
 *
 * @var modInstall $install
 * @var modInstallParser $parser
 * @var modInstallRequest $this
 * 
 * @package setup
 */
if ($install->settings->get('cleanup')) {
    $install->removeSetupDirectory();
}
$install->settings->erase();
$managerUrl= $install->getManagerLoginUrl();
header('Location: ' . $managerUrl);
exit();