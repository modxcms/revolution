<?php
/**
 * @var modInstall $install
 * @var modInstallParser $parser
 * @var modInstallRequest $this
 * 
 * @package setup
 */
$install->settings->check();
$default_folder_permissions = sprintf("%04o", 0777 & (0777 - umask()));
$default_file_permissions = sprintf("%04o", 0666 & (0666 - umask()));

if (!empty($_POST['proceed'])) {
    unset($_POST['proceed']);
    $_POST['installmode'] = isset ($_POST['installmode']) ? intval($_POST['installmode']) : modInstall::MODE_NEW;

    /* if upgrading from evo/revo, get old settings */
    $settings = $install->request->getConfig($_POST['installmode']);
    /* merge those with POST */
    $settings = array_merge($settings,$_POST);

    if (!empty($_POST['new_folder_permissions']) && $_POST['new_folder_permissions'] == $default_folder_permissions) {
        unset($settings['new_folder_permissions']);
        $install->settings->delete('new_folder_permissions');
    }
    if (!empty($_POST['new_file_permissions']) && $_POST['new_file_permissions'] == $default_file_permissions) {
        unset($settings['new_file_permissions']);
        $install->settings->delete('new_file_permissions');
    }

    /* then store in cache */
    $install->settings->store($settings);

    $installmode = $install->settings->get('installmode');
    if (in_array($installmode,array(modInstall::MODE_UPGRADE_REVO_ADVANCED,modInstall::MODE_NEW))) {
        $this->proceed('database');
    } else {
        $this->proceed('summary');
    }

}
$installmode = $install->settings->get('installmode',$install->getInstallMode());
$parser->set('installmode',$installmode);

$files_exist= 0;
if (file_exists(MODX_INSTALL_PATH . 'manager/index.php') &&
    file_exists(MODX_INSTALL_PATH . 'index.php') &&
    file_exists(MODX_INSTALL_PATH . 'connectors/index.php')
) {
    $files_exist = !in_array(MODX_SETUP_KEY, array('@advanced@', '@sdk@')) ? 1 : 0;
}

$manifest= 0;
if (file_exists(MODX_CORE_PATH . 'packages/core/manifest.php')) {
    $zipTime = filemtime(MODX_CORE_PATH . 'packages/core.transport.zip');
    $manifestTime = filemtime(MODX_CORE_PATH . 'packages/core/manifest.php');
    $manifest= $manifestTime <= $zipTime && $zipTime - 60 < $manifestTime ? 1 : 0;
}

$unpacked= 0;
if ($manifest && file_exists(MODX_CORE_PATH . 'packages/core/modWorkspace/')) {
    $unpacked= 1;
}

$safe_mode= @ ini_get('safe_mode');
$parser->set('safe_mode', ($safe_mode ? 1 : 0));

$settings = $install->settings->fetch();
$nfop = !empty($settings['new_folder_permissions']) ? $settings['new_folder_permissions'] : $default_folder_permissions;
$nfip = !empty($settings['new_file_permissions']) ? $settings['new_file_permissions'] : $default_file_permissions;

$parser->set('files_exist', $files_exist);
$parser->set('manifest', $manifest);
$parser->set('unpacked', $unpacked);
$parser->set('new_folder_permissions', $nfop);
$parser->set('new_file_permissions', $nfip);
$parser->set('default_folder_permissions', $default_folder_permissions);
$parser->set('default_file_permissions', $default_file_permissions);

return $parser->render('options.tpl');
