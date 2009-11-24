<?php
/**
 * @package setup
 */
if (!empty($_POST['proceed'])) {
    unset($_POST['proceed']);
    $_POST['database_charset'] = $_POST['database_connection_charset'];
    $install->settings->store($_POST);
    $mode = $install->settings->get('installmode');

    $errors = array();

    $install->getConnection();

    if (!is_object($install->xpdo) || !($install->xpdo instanceof xPDO)) {
        $errors['message'] = $install->lexicon['xpdo_err_ins'];
    } else if (!$install->xpdo->connect()) {
        /* allow this to pass for new installs only; will attempt to create during installation */
        if ($mode != modInstall::MODE_NEW) {
            $errors['message'] = $install->lexicon['db_err_connect_upgrade'];
        }
    }

    if ($mode == modInstall::MODE_NEW) {
        /* validate admin user data */
        if (empty ($_POST['cmsadmin'])) {
            $errors['cmsadmin'] = $install->lexicon['username_err_ns'];
        }
        if (empty ($_POST['cmsadminemail'])) {
            $errors['cmsadminemail'] = $install->lexicon['email_err_ns'];
        }
        if (empty ($_POST['cmspassword'])) {
            $errors['cmspassword'] = $install->lexicon['password_err_ns'];
        }
        if (empty ($_POST['cmspasswordconfirm'])) {
            $errors['cmspasswordconfirm'] = $install->lexicon['password_err_ns'];
        }
        if ($_POST['cmspasswordconfirm'] != $_POST['cmspassword']) {
            $errors['cmspasswordconfirm'] = $install->lexicon['password_err_nomatch'];
        }
    }
    /* TODO: need to do more error checking here... */

    /* if errors, output */
    if (!empty($errors)) {
        $this->parser->assign('errors_message',$install->lexicon['err_occ']);
        foreach ($errors as $k => $v) {
            $this->parser->assign('error_'.$k,$v);
        }
    } else { /* proceed to contexts page */
        $this->proceed('contexts');
    }
}
$mode = $install->settings->get('installmode');
$this->parser->assign('installmode', $mode);
$this->parser->assign('action',MODX_SETUP_KEY == '@traditional' ? 'summary' : 'contexts');

return $this->parser->fetch('database.tpl');