<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder
 */
$modx->lexicon->load('workspace','package_builder');

if (!$modx->hasPermission('package_builder')) return $modx->error->failure($modx->lexicon('permission_denied'));

$keys = array(
    array('key' => 'modAction'),
    array('key' => 'modCategory'),
    array('key' => 'modChunk'),
    array('key' => 'modContext'),
    array('key' => 'modDocument'),
    array('key' => 'modMenu'),
    array('key' => 'modPlugin'),
    array('key' => 'modResource'),
    array('key' => 'modSnippet'),
    array('key' => 'modTemplate'),
    array('key' => 'modTemplateVar'),
);

return $this->outputArray($keys);