<?php
/**
 * Loads the search page
 *
 * @package modx
 * @subpackage manager.search
 */
if (!$modx->hasPermission('search')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/modx.panel.search.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/search.js');

$modx->smarty->assign('_pagetitle',$modx->lexicon('search'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('search/search.tpl');