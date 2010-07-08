<?php
/**
 * @package setup
 */
$install->settings->check();
if (!empty($_POST['proceed'])) {
    unset($_POST['proceed']);
    $install->settings->store($_POST);

    $settings = array();
    $webUrl= substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'setup/'));
    $settings['core_path'] = MODX_CORE_PATH;
    $settings['web_path_auto'] = isset ($_POST['context_web_path_toggle']) && $_POST['context_web_path_toggle'] ? 1 : 0;
    $settings['web_path'] = isset($_POST['context_web_path']) ? $_POST['context_web_path'] : MODX_INSTALL_PATH;
    $settings['web_url_auto'] = isset ($_POST['context_web_url_toggle']) && $_POST['context_web_url_toggle'] ? 1 : 0;
    $settings['web_url'] = isset($_POST['context_web_url']) ? $_POST['context_web_url'] : $webUrl;
    $settings['mgr_path_auto'] = isset ($_POST['context_mgr_path_toggle']) && $_POST['context_mgr_path_toggle'] ? 1 : 0;
    $settings['mgr_path'] = isset($_POST['context_mgr_path']) ? $_POST['context_mgr_path'] : MODX_INSTALL_PATH . 'manager/';
    $settings['mgr_url_auto'] = isset ($_POST['context_mgr_url_toggle']) && $_POST['context_mgr_url_toggle'] ? 1 : 0;
    $settings['mgr_url'] = isset($_POST['context_mgr_url']) ? $_POST['context_mgr_url'] : $webUrl . 'manager/';
    $settings['connectors_path_auto'] = isset ($_POST['context_connectors_path_toggle']) && $_POST['context_connectors_path_toggle'] ? 1 : 0;
    $settings['connectors_path'] = isset($_POST['context_connectors_path']) ? $_POST['context_connectors_path'] : MODX_INSTALL_PATH . 'connectors/';
    $settings['connectors_url_auto'] = isset ($_POST['context_connectors_url_toggle']) && $_POST['context_connectors_url_toggle'] ? 1 : 0;
    $settings['connectors_url'] = isset($_POST['context_connectors_url']) ? $_POST['context_connectors_url'] : $webUrl . 'connectors/';
    $settings['processors_path'] = MODX_CORE_PATH . 'model/modx/processors/';
    $settings['assets_path'] = $settings['web_path'] . 'assets/';
    $settings['assets_url'] = $settings['web_url'] . 'assets/';
    $install->settings->store($settings);

    $this->proceed('summary');
}
$mode = $install->settings->get('installmode');

$webUrl= substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'setup/'));
if ($mode == modInstall::MODE_UPGRADE_REVO) {
    include MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';

    $this->parser->assign('context_web_path', defined('MODX_BASE_PATH') ? MODX_BASE_PATH : MODX_INSTALL_PATH);
    $this->parser->assign('context_web_url', defined('MODX_BASE_URL') ? MODX_BASE_URL : $webUrl);
    $this->parser->assign('context_connectors_path', defined('MODX_CONNECTORS_PATH') ? MODX_CONNECTORS_PATH : MODX_INSTALL_PATH . 'connectors/');
    $this->parser->assign('context_connectors_url', defined('MODX_CONNECTORS_URL') ? MODX_CONNECTORS_URL : $webUrl . 'connectors/');
    $this->parser->assign('context_mgr_path', defined('MODX_MANAGER_PATH') ? MODX_MANAGER_PATH : MODX_INSTALL_PATH . 'manager/');
    $this->parser->assign('context_mgr_url', defined('MODX_MANAGER_URL') ? MODX_MANAGER_URL : $webUrl . 'manager/');
} else {
    $this->parser->assign('context_web_path', MODX_INSTALL_PATH);
    $this->parser->assign('context_web_url', $webUrl);
    $this->parser->assign('context_connectors_path', MODX_INSTALL_PATH . 'connectors/');
    $this->parser->assign('context_connectors_url', $webUrl . 'connectors/');
    $this->parser->assign('context_mgr_path', MODX_INSTALL_PATH . 'manager/');
    $this->parser->assign('context_mgr_url', $webUrl . 'manager/');
}

return $this->parser->fetch('contexts.tpl');