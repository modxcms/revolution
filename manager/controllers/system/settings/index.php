<?php
/**
 * Loads the system settings page
 *
 * @package modx
 * @subpackage manager.system.settings
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/core/modx.grid.settings.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/system/modx.panel.system.settings.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/sections/system/settings.js');

return $modx->smarty->fetch('system/settings/index.tpl');