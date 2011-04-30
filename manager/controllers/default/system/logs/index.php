<?php
/**
 * Loads the manager logs page
 *
 * @package modx
 * @subpackage manager.system.logs
 */
if (!$modx->hasPermission('logs')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.grid.manager.log.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/logs.js');

$modx->smarty->assign('_pagetitle',$modx->lexicon('manager_log'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('system/logs/index.tpl');