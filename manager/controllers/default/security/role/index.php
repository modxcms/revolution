<?php
/**
 * Loads role management
 *
 * @package modx
 * @subpackage manager.security.role
 */
if (!$modx->hasPermission('view_role')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->assign('_pagetitle',$modx->lexicon('roles'));
$this->checkFormCustomizationRules();
return $modx->smarty->fetch('security/role/list.tpl');