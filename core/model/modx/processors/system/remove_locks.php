<?php
/**
 * Removes locks on all objects
 *
 * @package modx
 * @subpackage processors.system
 */
if (!$modx->hasPermission('remove_locks')) return $modx->error->failure($modx->lexicon('permission_denied'));

if ($modx->getService('registry', 'registry.modRegistry')) {
    $modx->registry->addRegister('locks', 'registry.modDbRegister', array('directory' => 'locks'));
    $modx->registry->locks->connect();
    $modx->registry->locks->subscribe('/resource/');
    $modx->registry->locks->read(array('remove_read' => true, 'poll_limit' => 1, 'msg_limit' => 1000));
}

return $modx->error->success();