<?php
/**
 * Loads the system events page
 *
 * @package modx
 * @subpackage manager.system.event
 */
if (!$modx->hasPermission('view_eventlog')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/system/modx.panel.error.log.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/system/event.js');

return $modx->smarty->fetch('system/event/list.tpl');