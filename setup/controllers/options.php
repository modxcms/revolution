<?php
/**
 * @package setup
 */
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

$navbar= '
<button id="cmdnext" name="cmdnext" onclick="return doAction(\'options\');">'.$install->lexicon['next'].'</button>
<button id="cmdback" name="cmdback" onclick="return goAction(\'welcome\');">'.$install->lexicon['back'].'</button>
';
$this->parser->assign('navbar', $navbar);
$this->parser->display('options.tpl');