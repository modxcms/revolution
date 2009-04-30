<?php
/**
 * Handle any final cleanups and redirect to login screen
 *
 * @package setup
 */
if (isset($_POST['cleanup']) && $_POST['cleanup']) {
    $install->removeSetupDirectory($_POST);
}
$managerUrl= $install->getManagerLoginUrl();
header('Location: ' . $managerUrl);
exit();