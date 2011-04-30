<?php
/**
 * Loads the access permissions page
 *
 * @package modx
 * @subpackage manager.security.permission
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('access_denied'));


/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.access.policy.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.access.policy.template.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.tree.user.group.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.role.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.panel.groups.roles.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/security/permissions/list.js');

$modx->smarty->assign('_pagetitle',$modx->lexicon('user_group_management'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('security/permissions/index.tpl');