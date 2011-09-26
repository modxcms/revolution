<?php
/**
 * @var modInstall $install
 * @var modInstallParser $parser
 * @var modInstallRequest $this
 * 
 * @package setup
 */
$install->settings->check();
if (!empty($_POST['proceed'])) {
    unset($_POST['proceed']);
    if (isset($_POST['database_connection_charset'])) {
        $_POST['database_charset'] = $_POST['database_connection_charset'];
    }
    $install->settings->store($_POST);
    $mode = $install->settings->get('installmode');

    $errors = array();

    $install->getConnection();

    if (!is_object($install->xpdo) || !($install->xpdo instanceof xPDO)) {
        $errors['message'] = $install->lexicon('xpdo_err_ins');
    } else if (!$install->xpdo->connect()) {
        /* allow this to pass for new installs only; will attempt to create during installation */
        if ($mode != modInstall::MODE_NEW) {
            $errors['message'] = $install->lexicon('db_err_connect_upgrade');
        }
    }

    if ($mode == modInstall::MODE_NEW) {
        /* validate admin user data */

        $invchars = array('/','\'','"','{','}','(',')');

        if (empty ($_POST['cmsadmin'])) {
            $errors['cmsadmin'] = $install->lexicon('username_err_ns');
        } else {

            $found = false;
            foreach ($invchars as $i) {
                if (strpos($_POST['cmsadmin'],$i) !== false) {
                    $found = true;
                }
            }
            if ($found) {
                $errors['cmsadmin'] = $install->lexicon('username_err_invchars');
            }
        }
        if (empty ($_POST['cmsadminemail'])) {
            $errors['cmsadminemail'] = $install->lexicon('email_err_ns');
        }
        if (empty ($_POST['cmspassword'])) {
            $errors['cmspassword'] = $install->lexicon('password_err_ns');
        } else {
            if (strlen($_POST['cmspassword']) < 6) {
                $errors['cmspassword'] = $install->lexicon('password_err_short');
            }

            $found = false;
            foreach ($invchars as $i) {
                if (strpos($_POST['cmspassword'],$i) !== false) {
                    $found = true;
                }
            }
            if ($found) {
                $errors['cmspassword'] = $install->lexicon('password_err_invchars');
            }
        }
        if (empty ($_POST['cmspasswordconfirm'])) {
            $errors['cmspasswordconfirm'] = $install->lexicon('password_err_ns');
        }
        if ($_POST['cmspasswordconfirm'] != $_POST['cmspassword']) {
            $errors['cmspasswordconfirm'] = $install->lexicon('password_err_nomatch');
        }
    }

    /* TODO: need to do more error checking here... */

    /* if errors, output */
    if (!empty($errors)) {
        $parser->set('config',$install->settings->fetch());
        $parser->set('showHidden',true);
        $parser->set('errors_message',$install->lexicon('err_occ'));
        foreach ($errors as $k => $v) {
            $parser->set('error_'.$k,$v);
        }
    } else { /* proceed to contexts page, or summary if @traditional */
        switch (MODX_SETUP_KEY) {
            case '@traditional@':
                $webUrl= substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'setup/'));
                $settings = array();

                if ($mode == modInstall::MODE_NEW) {
                    $settings['core_path'] = MODX_CORE_PATH;
                    $settings['web_path'] = MODX_INSTALL_PATH;
                    $settings['web_url'] = $webUrl;
                    $settings['mgr_path'] = MODX_INSTALL_PATH . 'manager/';
                    $settings['mgr_url'] = $webUrl . 'manager/';
                    $settings['connectors_path'] = MODX_INSTALL_PATH . 'connectors/';
                    $settings['connectors_url'] = $webUrl . 'connectors/';
                    $settings['processors_path'] = MODX_CORE_PATH . 'model/modx/processors/';
                    $settings['assets_path'] = $settings['web_path'] . 'assets/';
                    $settings['assets_url'] = $settings['web_url'] . 'assets/';
                } elseif ($mode == modInstall::MODE_UPGRADE_REVO || $mode == modInstall::MODE_UPGRADE_REVO_ADVANCED) {
                    include MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';

                    $settings['core_path'] = MODX_CORE_PATH;
                    $settings['web_path'] = defined('MODX_BASE_PATH') ? MODX_BASE_PATH : MODX_INSTALL_PATH;
                    $settings['web_url'] = defined('MODX_BASE_URL') ? MODX_BASE_URL : $webUrl;
                    $settings['connectors_path'] = defined('MODX_CONNECTORS_PATH') ? MODX_CONNECTORS_PATH : MODX_INSTALL_PATH . 'connectors/';
                    $settings['connectors_url'] = defined('MODX_CONNECTORS_URL') ? MODX_CONNECTORS_URL : $webUrl . 'connectors/';
                    $settings['mgr_path'] = defined('MODX_MANAGER_PATH') ? MODX_MANAGER_PATH : MODX_INSTALL_PATH . 'manager/';
                    $settings['mgr_url'] = defined('MODX_MANAGER_URL') ? MODX_MANAGER_URL : $webUrl . 'manager/';
                    $settings['assets_path'] = defined('MODX_ASSETS_PATH') ? MODX_ASSETS_PATH : $settings['web_path'] . 'assets/';
                    $settings['assets_url'] = defined('MODX_ASSETS_URL') ? MODX_ASSETS_URL : $settings['web_url'] . 'assets/';
                    $settings['processors_path'] = defined('MODX_PROCESSORS_PATH') ? MODX_PROCESSORS_PATH : MODX_CORE_PATH . 'model/modx/processors/';
                }
                $install->settings->store($settings);
                $this->proceed('summary');
                break;
            default:
                $this->proceed('contexts');
        }
    }
}
$mode = $install->settings->get('installmode');
$parser->set('installmode', $mode);
$parser->set('config',$install->settings->fetch());

return $parser->render('database.tpl');
