<?php
/**
 * Loads the help page
 *
 * @package modx
 * @subpackage manager
 */
if (!$modx->hasPermission('help')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* load JS scripts for page */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/help.js');

$modx->smarty->assign('_pagetitle',$modx->lexicon('help'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('help.tpl');