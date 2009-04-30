<?php
/**
 * @package setup
 */
/* validate database settings */
$errors= $install->cleanup($_POST);
if (!empty ($errors)) {
    $error->setType('error');
    $this->error->failure(implode('', $errors));
}
$response= 'login';
$this->error->success($response);
