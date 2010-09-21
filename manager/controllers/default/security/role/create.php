<?php
/**
 * Loads the create role page
 *
 * @package modx
 * @subpackage manager.security.role
 */
if(!$modx->hasPermission('new_role')) return $modx->error->failure($modx->lexicon('access_denied'));

$this->checkFormCustomizationRules();
return $modx->smarty->fetch('security/role/create.tpl');