<?php
$mode= isset ($_POST['installmode']) ? intval($_POST['installmode']) : MODX_INSTALL_MODE_NEW;
/* validate database settings */
$install->setConfig($mode);
$install->getConnection($mode);
if (!is_object($install->xpdo)) {
    $this->error->failure('<p>'.$install->lexicon['xpdo_err_ins'].'</p>');
}
if (!$install->xpdo->connect()) {
    /* allow this to pass for new installs only; will attempt to create during installation */
    if ($mode != MODX_INSTALL_MODE_NEW) {
        $this->error->failure('<p>'.$install->lexicon['db_err_connect_upgrade'].'</p>');
    }
}
if ($mode == MODX_INSTALL_MODE_NEW) {
    /* validate admin user data */
    $install->getAdminUser();
    if (empty ($install->config['cmsadmin'])) {
        $this->error->addField('cmsadmin',$install->lexicon['username_err_ns']);
    }
    if (empty ($install->config['cmsadminemail'])) {
        $this->error->addField('cmsadminemail',$install->lexicon['email_err_ns']);
    }
    if (empty ($install->config['cmspassword'])) {
        $this->error->addField('cmspassword',$install->lexicon['password_err_ns']);
    }
    if ($install->config['cmspasswordconfirm'] != $install->config['cmspassword']) {
    	$this->error->addField('cmspasswordconfirm',$install->lexicon['password_err_nomatch']);
    }
    if (count($error->fields)) {
        $this->error->failure($install->lexicon['err_occ']);
    }
}
$response= 'contexts';
$this->error->success($response);
