<?php
/**
 * Handle any final cleanups and redirect to login screen
 *
 * @package setup
 */
$managerUrl= $install->getManagerLoginUrl();
header('Location: ' . $managerUrl);
exit();