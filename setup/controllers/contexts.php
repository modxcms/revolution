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
    $webUrl= substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'setup/'));

    $_POST['context_web_path'] = !empty($_POST['context_web_path']) ? rtrim($_POST['context_web_path'],'/').'/' : MODX_INSTALL_PATH;
    $_POST['context_web_url'] = !empty($_POST['context_web_url']) ? rtrim($_POST['context_web_url'],'/').'/' : $webUrl;
    $_POST['context_mgr_path'] = !empty($_POST['context_mgr_path']) ? rtrim($_POST['context_mgr_path'],'/').'/' : MODX_INSTALL_PATH . 'manager/';
    $_POST['context_mgr_url'] = !empty($_POST['context_mgr_url']) ? rtrim($_POST['context_mgr_url'],'/').'/' : $webUrl . 'manager/';
    $_POST['context_connectors_path'] = !empty($_POST['context_connectors_path']) ? rtrim($_POST['context_connectors_path'],'/').'/' : MODX_INSTALL_PATH . 'connectors/';
    $_POST['context_connectors_url'] = !empty($_POST['context_connectors_url']) ? rtrim($_POST['context_connectors_url'],'/').'/' : $webUrl . 'connectors/';
    $install->settings->store($_POST);

    $settings = array();
    $settings['core_path'] = MODX_CORE_PATH;
    $settings['web_path_auto'] = isset ($_POST['context_web_path_toggle']) && $_POST['context_web_path_toggle'] ? 1 : 0;
    $settings['web_path'] = isset($_POST['context_web_path']) ? rtrim($_POST['context_web_path'],'/').'/' : MODX_INSTALL_PATH;
    $settings['web_url_auto'] = isset ($_POST['context_web_url_toggle']) && $_POST['context_web_url_toggle'] ? 1 : 0;
    $settings['web_url'] = isset($_POST['context_web_url']) ? rtrim($_POST['context_web_url'],'/').'/' : $webUrl;
    $settings['mgr_path_auto'] = isset ($_POST['context_mgr_path_toggle']) && $_POST['context_mgr_path_toggle'] ? 1 : 0;
    $settings['mgr_path'] = isset($_POST['context_mgr_path']) ? rtrim($_POST['context_mgr_path'],'/').'/' : MODX_INSTALL_PATH . 'manager/';
    $settings['mgr_url_auto'] = isset ($_POST['context_mgr_url_toggle']) && $_POST['context_mgr_url_toggle'] ? 1 : 0;
    $settings['mgr_url'] = isset($_POST['context_mgr_url']) ? rtrim($_POST['context_mgr_url'],'/').'/' : $webUrl . 'manager/';
    $settings['connectors_path_auto'] = isset ($_POST['context_connectors_path_toggle']) && $_POST['context_connectors_path_toggle'] ? 1 : 0;
    $settings['connectors_path'] = isset($_POST['context_connectors_path']) ? rtrim($_POST['context_connectors_path'],'/').'/' : MODX_INSTALL_PATH . 'connectors/';
    $settings['connectors_url_auto'] = isset ($_POST['context_connectors_url_toggle']) && $_POST['context_connectors_url_toggle'] ? 1 : 0;
    $settings['connectors_url'] = isset($_POST['context_connectors_url']) ? rtrim($_POST['context_connectors_url'],'/').'/' : $webUrl . 'connectors/';
    $settings['processors_path'] = MODX_CORE_PATH . 'model/modx/processors/';
    $settings['assets_path'] = $settings['web_path'] . 'assets/';
    $settings['assets_url'] = $settings['web_url'] . 'assets/';
    $install->settings->store($settings);

    $this->proceed('summary');
}
$mode = $install->settings->get('installmode');

$webUrl= substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'setup/'));
if ($mode == modInstall::MODE_UPGRADE_REVO || $mode == modInstall::MODE_UPGRADE_REVO_ADVANCED) {
    include MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';

    $parser->set('context_web_path', defined('MODX_BASE_PATH') ? MODX_BASE_PATH : MODX_INSTALL_PATH);
    $parser->set('context_web_url', defined('MODX_BASE_URL') ? MODX_BASE_URL : $webUrl);
    $parser->set('context_connectors_path', defined('MODX_CONNECTORS_PATH') ? MODX_CONNECTORS_PATH : MODX_INSTALL_PATH . 'connectors/');
    $parser->set('context_connectors_url', defined('MODX_CONNECTORS_URL') ? MODX_CONNECTORS_URL : $webUrl . 'connectors/');
    $parser->set('context_mgr_path', defined('MODX_MANAGER_PATH') ? MODX_MANAGER_PATH : MODX_INSTALL_PATH . 'manager/');
    $parser->set('context_mgr_url', defined('MODX_MANAGER_URL') ? MODX_MANAGER_URL : $webUrl . 'manager/');
} else {
    $parser->set('context_web_path', MODX_INSTALL_PATH);
    $parser->set('context_web_url', $webUrl);
    $parser->set('context_connectors_path', MODX_INSTALL_PATH . 'connectors/');
    $parser->set('context_connectors_url', $webUrl . 'connectors/');
    $parser->set('context_mgr_path', MODX_INSTALL_PATH . 'manager/');
    $parser->set('context_mgr_url', $webUrl . 'manager/');
}

return $parser->render('contexts.tpl');