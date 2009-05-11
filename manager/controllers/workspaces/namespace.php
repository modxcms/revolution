<?php
/**
 * Loads lexicon management
 *
 * @package modx
 * @subpackage manager.workspaces
 */
if (!$modx->hasPermission('namespaces')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/namespace/modx.namespace.panel.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/namespace/index.js');

return $modx->smarty->fetch('workspaces/namespace/index.tpl');