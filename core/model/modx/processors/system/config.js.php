<?php
/**
 * Outputs the $modx->config to JSON
 *
 * @param string $action If set with context, will output the context info for a
 * custom context by the action
 * @param string $context If set with action, will output the context info for a
 * custom context by its action
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @package modx
 * @subpackage processors.system
 */
if (!$modx->user->isAuthenticated('mgr')) { return ''; }
$modx->getVersionData();

$wctx = isset($scriptProperties['wctx']) && !empty($scriptProperties['wctx']) ? $scriptProperties['wctx'] : '';
if (!empty($wctx)) {
    $workingContext = $modx->getContext($wctx);
    if (!$workingContext) {
        return $modx->error->failure($modx->error->failure($modx->lexicon('permission_denied')));
    }
} else {
    $workingContext =& $modx->context;
}

/* calculate custom resource classes */
$modx->lexicon->load('resource');
$resourceClasses = array();
$resourceClassesDrop = array();
$resourceClassNames = $modx->getDescendants('modResource');
$resourceClassNames = array_diff($resourceClassNames,array('modResource'));
foreach ($resourceClassNames as $resourceClassName) {
    $obj = $modx->newObject($resourceClassName);
    /** @var modResource $obj */
    if ($obj->showInContextMenu) {
        $lex = $obj->getContextMenuText();
        $resourceClasses[$resourceClassName] = $lex;
    }

    if ($obj->allowDrop != -1) {
        $resourceClassesDrop[$resourceClassName] = $obj->allowDrop;
    }
}

$template_url = $workingContext->getOption('manager_url', MODX_MANAGER_URL, $modx->_userConfig) . 'templates/' . $workingContext->getOption('manager_theme', 'default', $modx->_userConfig) . '/';
$c = array(
    'base_url' => $workingContext->getOption('base_url', MODX_BASE_URL, $modx->_userConfig),
    'connectors_url' => $workingContext->getOption('connectors_url', MODX_CONNECTORS_URL, $modx->_userConfig),
    'icons_url' => $template_url . 'images/ext/modext/',
    'manager_url' => $workingContext->getOption('manager_url', MODX_MANAGER_URL, $modx->_userConfig),
    'template_url' => $template_url,
    'http_host' => $workingContext->getOption('http_host', MODX_HTTP_HOST, $modx->_userConfig),
    'site_url' => $workingContext->getOption('site_url', MODX_SITE_URL, $modx->_userConfig),
    'http_host_remote' => MODX_URL_SCHEME . $workingContext->getOption('http_host', MODX_HTTP_HOST, $modx->_userConfig),
    'user' => $modx->user->get('id'),
    'version' => $modx->version['full_version'],
    'resource_classes' => $resourceClasses,
    'resource_classes_drop' => $resourceClassesDrop,
);

// Handle default context
$ctx = $modx->getContext($modx->getOption('default_context', null, 'web'));
if ($ctx instanceof modContext && $ctx->prepare()) {
    $c['default_site_url'] = $ctx->makeUrl($ctx->getOption('site_start'));
}

/* if custom context, load into MODx.config */
if (isset($scriptProperties['action']) && $scriptProperties['action'] != '' && isset($modx->actionMap[$scriptProperties['action']])) {

    /* pre-2.3 actions */
    if (intval($scriptProperties['action']) > 0) {
        $action = $modx->actionMap[$scriptProperties['action']];
        $c['namespace'] = $action['namespace'];
        $c['namespace_path'] = $action['namespace_path'];
        $c['namespace_assets_path'] = $action['namespace_assets_path'];
        $c['help_url'] = ltrim($action['help_url'],'/');
    } else {
        $namespace = $modx->getOption('namespace',$scriptProperties,'core');
        /** @var modNamespace $namespace */
        $namespace = $this->modx->getObject('modNamespace',$namespace);
        if ($namespace) {
            $c['namespace'] = $namespace->get('name');
            $c['namespace_path'] = $namespace->get('path');
            $c['namespace_assets_path'] = $namespace->get('assets_url');
        }
    }
}

$c = array_merge($modx->config,$workingContext->config,$modx->_userConfig,$c);

unset($c['password'],$c['username'],$c['mail_smtp_pass'],$c['mail_smtp_user'],$c['proxy_password'],$c['proxy_username'],$c['connections'],$c['connection_init'],$c['connection_mutable'],$c['dbname'],$c['database'],$c['table_prefix'],$c['driverOptions'],$c['dsn'],$c['session_name'], $c['assets_path'], $c['base_path'], $c['cache_path'], $c['connectors_path'], $c['core_path'], $c['friendly_alias_translit_class_path'], $c['manager_path'], $c['processors_path']);

$o = "Ext.namespace('MODx'); MODx.config = ";
$o .= $modx->toJSON($c);
$o .= '; MODx.perm = {};';

// Load actions for backwards compatibility (DEPRECATED)
$actions = $modx->request->getAllActionIDs();
$o .= 'MODx.action = ' . $modx->toJSON($actions) . ';';

if ($modx->user) {
    if ($modx->hasPermission('directory_create')) { $o .= 'MODx.perm.directory_create = true;'; }
    if ($modx->hasPermission('resource_tree')) { $o .= 'MODx.perm.resource_tree = true;'; }
    if ($modx->hasPermission('element_tree')) { $o .= 'MODx.perm.element_tree = true;'; }
    if ($modx->hasPermission('file_tree')) { $o .= 'MODx.perm.file_tree = true;'; }
    if ($modx->hasPermission('file_upload')) { $o .= 'MODx.perm.file_upload = true;'; }
    if ($modx->hasPermission('file_create')) { $o .= 'MODx.perm.file_create = true;'; }
    if ($modx->hasPermission('file_manager')) { $o .= 'MODx.perm.file_manager = true;'; }
    if ($modx->hasPermission('new_chunk')) { $o .= 'MODx.perm.new_chunk  = true;'; }
    if ($modx->hasPermission('new_plugin')) { $o .= 'MODx.perm.new_plugin = true;'; }
    if ($modx->hasPermission('new_snippet')) { $o .= 'MODx.perm.new_snippet = true;'; }
    if ($modx->hasPermission('new_template')) { $o .= 'MODx.perm.new_template = true;'; }
    if ($modx->hasPermission('new_tv')) { $o .= 'MODx.perm.new_tv = true;'; }
    if ($modx->hasPermission('new_category')) { $o .= 'MODx.perm.new_category = true;'; }
    if ($modx->hasPermission('resourcegroup_resource_edit')) { $o .= 'MODx.perm.resourcegroup_resource_edit = true;'; }
    if ($modx->hasPermission('resourcegroup_resource_list')) { $o .= 'MODx.perm.resourcegroup_resource_list = true;'; }

    $o .= 'MODx.user = {id:"'.$modx->user->get('id').'",username:"'.$modx->user->get('username').'"}';
}
@session_write_close();
header('Content-Type: application/x-javascript');
echo $o;
die();
