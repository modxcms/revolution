<?php
/**
 * Views a workspace
 *
 * @param integer $id The ID of the workspace
 *
 * @package modx
 * @subpackage processors.workspace
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('workspaces')) return $modx->error->failure($modx->lexicon('permission_denied'));

$object= null;
if (isset($scriptProperties['id']) && $nodeId= intval($scriptProperties['id'])) {
    if ($workspace= $modx->getObject('modWorkspace', $nodeId)) {
        $object= $workspace->toArray();
    }
}
return $modx->error->success('', array ($object));