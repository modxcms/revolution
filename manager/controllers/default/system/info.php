<?php
/**
 * Loads the system info page
 *
 * @package modx
 * @subpackage controllers.system
 */
if (!$modx->hasPermission('view_sysinfo')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.grid.databasetables.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.grid.resource.active.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/info.js');

return $modx->smarty->fetch('system/info.tpl');