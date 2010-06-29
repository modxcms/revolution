<?php
/**
 * Loads the workspace package builder
 *
 * @package modx
 * @subpackage manager.workspaces
 */
if (!$modx->hasPermission('workspaces')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/combos.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package/package.versions.grid.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package/package.panel.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package/index.js');

return $modx->smarty->fetch('workspaces/package/view.tpl');