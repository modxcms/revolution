<?php
/**
 * Loads action management
 *
 * @package modx
 * @subpackage manager.system.action
 */
if (!$modx->hasPermission('actions')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.tree.action.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.tree.menu.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.panel.actions.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/action.js');

$modx->smarty->assign('_pagetitle',$modx->lexicon('actions'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('system/action/index.tpl');