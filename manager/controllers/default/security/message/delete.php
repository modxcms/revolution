<?php
/**
 * Loads the delete message processor 
 * 
 * @package modx
 * @subpackage manager.security.message
 */
if (!$modx->hasPermission('messages')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('security/message/delete.php');

// redirect
header('Location: index.php?a=security/message/list');
exit();