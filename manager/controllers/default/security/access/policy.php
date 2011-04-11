<?php
/**
 * Loads the policy management page
 *
 * @package modx
 * @subpackage manager.security.access
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.access.policy.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/security/access/policy.js');

$modx->smarty->assign('_pagetitle',$modx->lexicon('access_policies'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('security/access/policy.tpl');