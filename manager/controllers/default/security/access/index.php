<?php
/**
 * Loads groups/roles management
 *
 * @package modx
 * @subpackage manager.security.access
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.access.context.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.access.resourcegroup.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/security/access/list.js');

$modx->smarty->assign('_pagetitle',$modx->lexicon('user_group_management'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('security/access/index.tpl');