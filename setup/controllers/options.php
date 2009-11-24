<?php
/**
 * @package setup
 */
if (!empty($_POST['proceed'])) {
    unset($_POST['proceed']);
    $_POST['installmode'] = isset ($_POST['installmode']) ? intval($_POST['installmode']) : modInstall::MODE_NEW;

    /* if upgrading from evo/revo, get old settings */
    $settings = $install->getConfig($_POST['installmode']);
    /* merge those with POST */
    $settings = array_merge($settings,$_POST);
    /* then store in cache */
    $install->settings->store($settings);
    $this->proceed('database');
}
$this->parser->assign('installmode',$install->getInstallMode());

$files_exist= 0;
if (file_exists(MODX_INSTALL_PATH . 'manager/index.php') &&
	file_exists(MODX_INSTALL_PATH . 'index.php') &&
	file_exists(MODX_INSTALL_PATH . 'connectors/index.php')
) {
	$files_exist = !in_array(MODX_SETUP_KEY, array('@advanced', '@sdk')) ? 1 : 0;
}

$manifest= 0;
if (file_exists(MODX_CORE_PATH . 'packages/core/manifest.php')) {
    $manifest= 1;
}

$unpacked= 0;
if ($manifest && file_exists(MODX_CORE_PATH . 'packages/core/modWorkspace/')) {
    $unpacked= 1;
}

$safe_mode= @ ini_get('safe_mode');
$this->parser->assign('safe_mode', ($safe_mode ? 1 : 0));


$this->parser->assign('files_exist', $files_exist);
$this->parser->assign('manifest', $manifest);
$this->parser->assign('unpacked', $unpacked);

return $this->parser->fetch('options.tpl');