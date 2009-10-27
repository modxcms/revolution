<?php
/**
 * @package setup
 */
$mode= isset ($_POST['installmode']) ? intval($_POST['installmode']) : modInstall::MODE_NEW;
/* validate database settings */
$install->setConfig($mode);
if ($mode == modInstall::MODE_NEW) {
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