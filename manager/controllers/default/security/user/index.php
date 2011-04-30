<?php
/**
 * Loads the user list
 *
 * @package modx
 * @subpackage manager.security.user
 */
if(!$modx->hasPermission('edit_user')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.user.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/security/user/list.js');

$modx->smarty->assign('_pagetitle',$modx->lexicon('users'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('security/user/list.tpl');