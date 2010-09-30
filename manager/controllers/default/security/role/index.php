<?php
/**
 * Loads role management
 *
 * @package modx
 * @subpackage manager.security.role
 */
if (!$modx->hasPermission('view_role')) return $modx->error->failure($modx->lexicon('access_denied'));

$this->checkFormCustomizationRules();
return $modx->smarty->fetch('security/role/list.tpl');