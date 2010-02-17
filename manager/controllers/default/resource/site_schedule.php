<?php
/**
 * Loads the site schedule
 *
 * @package modx
 * @subpackage manager.resource
 */
if (!$modx->hasPermission('view_document')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.panel.resource.schedule.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/resource/schedule.js');

return $modx->smarty->fetch('resource/site_schedule.tpl');