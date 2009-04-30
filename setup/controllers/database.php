<?php
/**
 * @package setup
 */
$installMode= $install->getInstallMode();
$this->parser->assign('installmode', $installMode);


$install->getConfig($installMode);
if ($installMode == 0) {
    $install->getAdminUser();
}
$this->parser->assign('config', $install->config);

$action = MODX_SETUP_KEY == '@traditional' ? 'goAction(\'summary\')' : 'doAction(\'database\')';

$navbar= '
<button id="cmdnext" name="cmdnext" onclick="return ' . $action . ';" />'.$install->lexicon['next'].'</button>
<button id="cmdback" name="cmdback" onclick="return goAction(\'options\');">'.$install->lexicon['back'].'</button>
';
$this->parser->assign('navbar', $navbar);
$this->parser->display('database.tpl');