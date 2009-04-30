<?php
/**
 * Loads the site schedule
 *
 * @package modx
 * @subpackage manager.resource
 */

/* register JS scripts */
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/resource/modx.panel.resource.schedule.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/sections/resource/schedule.js');

return $modx->smarty->fetch('resource/site_schedule.tpl');