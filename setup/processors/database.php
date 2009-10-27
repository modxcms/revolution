<?php
$mode= isset ($_POST['installmode']) ? intval($_POST['installmode']) : modInstall::MODE_NEW;
/* validate database settings */
$install->setConfig($mode);
$err = $install->getConnection($mode);
if (!($err instanceof xPDO)) { $this->error->failure($err); }

if (!is_object($install->xpdo) || !($install->xpdo instanceof xPDO)) {
    $this->error->failure('<p>'.$install->lexicon['xpdo_err_ins'].'</p>');
}
if (!$install->xpdo->connect()) {
    /* allow this to pass for new installs only; will attempt to create during installation */
    if ($mode != modInstall::MODE_NEW) {
        $this->error->failure('<p>'.$install->lexicon['db_err_connect_upgrade'].'</p>');
    }
}
if ($mode == modInstall::MODE_NEW) {
    /* validate admin user data */
    $install->getAdminUser();
    if (empty ($_POST['cmsadmin'])) {
        $this->error->addField('cmsadmin',$install->lexicon['username_err_ns']);
    }
    if (empty ($_POST['cmsadminemail'])) {
        $this->error->addField('cmsadminemail',$install->lexicon['email_err_ns']);
    }
    if (empty ($_POST['cmspassword'])) {
        $this->error->addField('cmspassword',$install->lexicon['password_err_ns']);
    }
    if (empty ($_POST['cmspasswordconfirm'])) {
        $this->error->addField('cmspasswordconfirm',$install->lexicon['password_err_ns']);
    }
    if ($_POST['cmspasswordconfirm'] != $_POST['cmspassword']) {
    	$this->error->addField('cmspasswordconfirm',$install->lexicon['password_err_nomatch']);
    }
    if (count($error->fields)) {
        $this->error->failure($install->lexicon['err_occ']);
    }
}
$response= 'contexts';
$this->error->success($response);
