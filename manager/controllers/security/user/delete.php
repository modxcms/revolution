<?php
/**
 * Loads the delete user processor
 * 
 * @package modx
 * @subpackage manager.security.user
 */
if (!$modx->hasPermission('delete_user')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('security/user/delete.php');

header('Location: index.php?a=security/user/list');
exit();