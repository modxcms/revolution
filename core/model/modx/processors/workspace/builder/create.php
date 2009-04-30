<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder
 */
$modx->lexicon->load('workspace','package_builder');

if (!$modx->hasPermission('package_builder')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['name']) || $_POST['name'] == '') {
    return $modx->error->failure('Please specify a package name.');
}

if (!isset($_SESSION['modx.pb'])) $_SESSION['modx.pb'] = array();

$_SESSION['modx.pb']['name'] = strtolower($_POST['name']);
$_SESSION['modx.pb']['version'] = strtolower($_POST['version']);
$_SESSION['modx.pb']['release'] = strtolower($_POST['release']);
$_SESSION['modx.pb']['namespace'] = $_POST['namespace'];
$_SESSION['modx.pb']['vehicles'] = array();

return $modx->error->success();