<?php
/**
 * Loads message management
 *
 * @package modx
 * @subpackage manager.security.message
 */
if (!$modx->hasPermission('messages')) return $modx->error->failure($modx->lexicon('access_denied'));

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/security/modx.grid.message.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/security/message/list.js');

return $modx->smarty->fetch('security/message/list.tpl');