<?php
/**
 * @package setup
 */
$mode= isset ($_POST['installmode']) ? intval($_POST['installmode']) : modInstall::MODE_NEW;

/* validate database settings */
$install->setConfig($mode);
$err = $install->getConnection($mode);
if (!($err instanceof xPDO)) { $this->error->failure($err); }

if (!($install->xpdo instanceof xPDO) || !$install->xpdo->connect()) {
    $this->error->failure('<p>'.$install->lexicon['db_err_connect'].'</p><pre>' . print_r($install->config, true) . '</pre>');
}
$this->error->setType('success');
$this->error->failure('<p>'.$install->lexicon['db_connected'].'</p>');

$this->error->success($response);