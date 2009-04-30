<?php
/**
 * Loads the help page
 *
 * @package modx
 * @subpackage manager
 */
if (!$modx->hasPermission('help')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* load JS scripts for page */
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/sections/system/help.js');

return $modx->smarty->fetch('help.tpl');