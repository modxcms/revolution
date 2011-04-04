<?php
/**
 * Loads the resource group page
 *
 * @package modx
 * @subpackage manager.security.permission
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.tree.resource.simple.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.tree.resource.group.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.panel.resource.group.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/security/resourcegroup/list.js');

$modx->smarty->assign('_pagetitle',$modx->lexicon('resource_groups'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('security/resourcegroup/index.tpl');