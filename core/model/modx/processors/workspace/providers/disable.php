<?php
/**
 * Disable a provider
 *
 * @param integer $id The ID of the provider
 *
 * @package modx
 * @subpackage processors.workspace.providers
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));

return $modx->error->failure('Not yet implemented.');