<?php
/**
 * @package setup
 */
$installMode= $install->getInstallMode();
$this->parser->assign('installmode', $installMode);

$install->setConfig($installMode);
if ($installMode == MODX_INSTALL_MODE_NEW) {
    $install->getAdminUser();
}
$install->getContextPaths();
$this->parser->assign('config', $install->config);

$results= $install->execute($installMode);
$this->parser->assign('results', $results);

$failed= false;
foreach ($results as $item) {
    if ($item['class'] === 'failed') {
        $failed= true;
        break;
    }
}
$this->parser->assign('failed', $failed);
$this->parser->assign('itemClass', $failed ? 'error' : '');

$nextButton= $failed ? $install->lexicon['retry'] : $install->lexicon['continue'];
$nextAction= $failed ? 'return goAction(\'install\')' : 'return doAction(\'install\');';
$backButton= $failed ? '<button id="cmdback" name="cmdback" onclick="return goAction(\'contexts\');">'.$install->lexicon['back'].'</button>' : '';
$navbar= '
<button id="cmdnext" name="cmdnext" onclick="' . $nextAction . '" >'.$nextButton.'</button>
';
$this->parser->assign('navbar', $navbar);
$this->parser->display('install.tpl');