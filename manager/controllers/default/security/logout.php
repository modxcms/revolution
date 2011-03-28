<?php
/**
 * @package modx
 * @subpackage manager.security
 */
$modx->lexicon->load('access');
$modx->smarty->assign('_lang',$modx->lexicon->fetch());

if (!empty($_POST['logout'])) {
    $modx->runProcessor('security/logout');
    $url = $modx->getOption('manager_url');
    $modx->sendRedirect($url);
}

return $modx->smarty->fetch('security/logout.tpl');