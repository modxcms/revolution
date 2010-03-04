<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder.vehicle
 */
$modx->lexicon->load('workspace','package_builder');

if (!$modx->hasPermission('package_builder')) return $modx->error->failure($modx->lexicon('permission_denied'));

array_splice($_SESSION['modx.pb']['vehicles'],$scriptProperties['index'],1);

return $modx->error->success();