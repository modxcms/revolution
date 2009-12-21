<?php
/**
 * Loads the delete role page
 * 
 * @package modx
 * @subpackage manager.security.role
 */
if (!$modx->hasPermission('delete_role')) return $modx->error->failure($modx->lexicon('access_denied'));
$modx->loadProcessor('security/role/delete.php');

header('Location: index.php?a=security/role/list');
exit();