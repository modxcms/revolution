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

$results= $install->test($installMode);
$this->parser->assign('test', $results);

$failed= false;
foreach ($results as $item) {
    if ($item['class'] === 'testFailed') {
        $failed= true;
        break;
    }
}
$this->parser->assign('failed', $failed);
$this->parser->assign('testClass', $failed ? 'error' : 'success');

$nextButton= $failed ? $install->lexicon['retry'] : $install->lexicon['install'];
$nextAction= $failed ? 'return goAction(\'summary\')' : 'return goAction(\'install\');';

$prevAction= MODX_SETUP_KEY == '@traditional' ? 'return goAction(\'database\')' : 'return goAction(\'contexts\');';

$navbar= '
<button id="cmdnext" name="cmdnext" onclick="' . $nextAction . '">' . $nextButton . '</button>
<button id="cmdback" name="cmdback" onclick="' . $prevAction . '">'.$install->lexicon['back'].'</button>
';
$this->parser->assign('navbar', $navbar);
$this->parser->display('summary.tpl');
