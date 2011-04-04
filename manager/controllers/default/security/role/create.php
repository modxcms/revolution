<?php
/**
 * Loads the create role page
 *
 * @package modx
 * @subpackage manager.security.role
 */
if(!$modx->hasPermission('new_role')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->assign('_pagetitle',$modx->lexicon('role'));
return $modx->smarty->fetch('security/role/create.tpl');