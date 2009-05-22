<?php
/**
 * @package setup
 */
$mode= isset ($_POST['installmode']) ? intval($_POST['installmode']) : MODX_INSTALL_MODE_NEW;

/* validate database settings */
$install->setConfig();
$err = $install->getConnection();
if (!is_a($err,'xPDO')) { $this->error->failure($err); }

if (!is_a($install->xpdo,'xPDO') || !$install->xpdo->connect()) {
    $this->error->failure('<p>'.$install->lexicon['db_err_connect'].'</p><pre>' . print_r($install->config, true) . '</pre>');
}
$this->error->setType('success');
$this->error->failure('<p>'.$install->lexicon['db_connected'].'</p>');

$this->error->success($response);