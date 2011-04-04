<?php
/**
 * Loads the
 *
 * @package modx
 * @subpackage manager.security.role
 */
if(!$modx->hasPermission('edit_role')) return $modx->error->failure($modx->lexicon('access_denied'));

/* get role */
$role = $modx->getObject('modUserGroupRole',$_REQUEST['id']);
if ($role == null) return $modx->error->failure($modx->lexicon('role_err_nf'));


$modx->smarty->assign('role',$role);
$modx->smarty->assign('_pagetitle',$modx->lexicon('role').': '.$role->get('role'));
return $modx->smarty->fetch('security/role/update.tpl');