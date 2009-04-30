<?php
/**
 * @package setup
 */
$mode= isset ($_POST['installmode']) ? intval($_POST['installmode']) : MODX_INSTALL_MODE_NEW;
/* validate database settings */
$install->setConfig($mode);
if ($mode == MODX_INSTALL_MODE_NEW) {
    $install->getAdminUser();
}

$install->getContextPaths();

$errors= $install->verify();
if (!empty ($errors)) {
    $this->error->setType('error');
    $this->error->failure(implode('', $errors));
}
$response= 'complete';

$this->error->success($response);