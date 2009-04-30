<?php
/**
 * Outputs the $modx->config to JSON
 *
 * @param string $action If set with context, will output the context info for a
 * custom context by the action
 * @param string $context If set with action, will output the context info for a
 * custom context by its action
 *
 * @package modx
 * @subpackage processors.system
 */
$stay = isset($_SESSION['modx.stay']) ? $_SESSION['modx.stay'] : 'stay';
$modx->getVersionData();

$template_url = $modx->config['manager_url'].'templates/'.$modx->config['manager_theme'].'/';
$c = array(
    'stay' => $stay,
    'base_url' => $modx->config['base_url'],
    'connectors_url' => $modx->config['connectors_url'],
    'icons_url' => $template_url.'images/ext/modext/',
    'manager_url' => $modx->config['manager_url'],
    'template_url' => $template_url,
    'user' => $modx->user->get('id'),
    'version' => $modx->version['full_version'],
);

/* if custom context, load into MODx.config */
if (isset($_REQUEST['action']) && $_REQUEST['action'] != '' && isset($modx->actionMap[$_REQUEST['action']])) {

    $action = $modx->actionMap[$_REQUEST['action']];
    $c['namespace'] = $action['namespace'];
    $c['namespace_path'] = $action['namespace_path'];
}

$actions = $modx->request->getAllActionIDs();

$c = array_merge($modx->config,$c);

$o = "Ext.namespace('MODx'); MODx.config = ";
$o .= $modx->toJSON($c);
$o .= '; MODx.action = ';
$o .= $modx->toJSON($actions);
$o .= '; MODx.perm = {};';

echo $o;
die();