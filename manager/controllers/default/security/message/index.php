<?php
/**
 * Loads message management
 *
 * @package modx
 * @subpackage manager.security.message
 */
if (!$modx->hasPermission('messages')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('security/message/list.tpl');