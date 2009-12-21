<?php
/**
 * Loads role management
 *
 * @package modx
 * @subpackage manager.security.role
 */
if(!$modx->hasPermission('edit_role')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('security/role/list.tpl');