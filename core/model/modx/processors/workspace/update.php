<?php
/**
 * Updates a workspace
 *
 * @package modx
 * @subpackage processors.workspace
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('workspaces')) return $modx->error->failure($modx->lexicon('permission_denied'));

return $modx->error->success();