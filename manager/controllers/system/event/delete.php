<?php
/**
 * Loads the delete system events page 
 * 
 * @package modx
 * @subpackage manager.system.event
 */
if (!$modx->hasPermission('delete_eventlog')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('system/event/delete.php');

header('Location: index.php?a=system/event/list');
exit();
