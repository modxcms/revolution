<?php
/**
 * Loads the workspace manager
 *
 * @package modx
 * @subpackage manager.workspaces
 */
if (!$modx->hasPermission('workspaces')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.tree.checkbox.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.panel.wizard.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package.browser.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package.download.panel.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package.add.panel.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package.install.window.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package.uninstall.window.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package.update.window.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/combos.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/package.grid.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/provider.grid.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/workspace.panel.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/index.js');

return $modx->smarty->fetch('workspaces/index.tpl');