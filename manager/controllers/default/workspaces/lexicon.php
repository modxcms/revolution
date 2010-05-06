<?php
/**
 * Loads lexicon management
 *
 * @package modx
 * @subpackage manager.workspaces
 */
if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.local.property.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/lexicon/combos.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/lexicon/lexicon.grid.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/lexicon/lexicon.panel.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/workspace/lexicon/index.js');

return $modx->smarty->fetch('workspaces/lexicon/index.tpl');