<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder
 */
$modx->lexicon->load('workspace','package_builder');

if (!$modx->hasPermission('package_builder')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($scriptProperties['name']) || $scriptProperties['name'] == '') {
    return $modx->error->failure('Please specify a package name.');
}

if (!isset($_SESSION['modx.pb'])) $_SESSION['modx.pb'] = array();

$_SESSION['modx.pb']['name'] = strtolower($scriptProperties['name']);
$_SESSION['modx.pb']['version'] = strtolower($scriptProperties['version']);
$_SESSION['modx.pb']['release'] = strtolower($scriptProperties['release']);
$_SESSION['modx.pb']['namespace'] = $scriptProperties['namespace'];
$_SESSION['modx.pb']['vehicles'] = array();

return $modx->error->success();