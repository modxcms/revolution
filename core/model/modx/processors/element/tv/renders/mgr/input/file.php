<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$modx->getService('fileHandler','modFileHandler', '', array('context' => $this->xpdo->context->get('key')));

/* get working context */
$wctx = isset($_GET['wctx']) && !empty($_GET['wctx']) ? $modx->sanitizeString($_GET['wctx']) : '';
if (!empty($wctx)) {
    $workingContext = $modx->getContext($wctx);
    if (!$workingContext) {
        return $modx->error->failure($modx->lexicon('permission_denied'));
    }
    $params['wctx'] = $workingContext->get('key');
} else {
    $params['wctx'] = $modx->context->get('key');
}


$value = $this->get('value');

/* get base path based on either TV param or filemanager_path */
$replacePaths = array(
    '[[++base_path]]' => $modx->getOption('base_path',null,MODX_BASE_PATH),
    '[[++core_path]]' => $modx->getOption('core_path',null,MODX_CORE_PATH),
    '[[++manager_path]]' => $modx->getOption('manager_path',null,MODX_MANAGER_PATH),
    '[[++assets_path]]' => $modx->getOption('assets_path',null,MODX_ASSETS_PATH),
    '[[++base_url]]' => $modx->getOption('base_url',null,MODX_BASE_URL),
    '[[++manager_url]]' => $modx->getOption('manager_url',null,MODX_MANAGER_URL),
    '[[++assets_url]]' => $modx->getOption('assets_url',null,MODX_ASSETS_URL),
);
$replaceKeys = array_keys($replacePaths);
$replaceValues = array_values($replacePaths);

if (empty($params['basePath'])) {
    $params['basePath'] = $modx->fileHandler->getBasePath();
    $params['basePath'] = str_replace($replaceKeys,$replaceValues,$params['basePath']);
    $params['basePathRelative'] = $this->xpdo->getOption('filemanager_path_relative',null,true) ? 1 : 0;
} else {
    $params['basePath'] = str_replace($replaceKeys,$replaceValues,$params['basePath']);
    $params['basePathRelative'] = !isset($params['basePathRelative']) || in_array($params['basePathRelative'],array('true',1,'1'));
}
if (empty($params['baseUrl'])) {
    $params['baseUrl'] = $modx->fileHandler->getBaseUrl();
    $params['baseUrl'] = str_replace($replaceKeys,$replaceValues,$params['baseUrl']);
    $params['baseUrlRelative'] = $this->xpdo->getOption('filemanager_url_relative',null,true) ? 1 : 0;
} else {
    $params['baseUrl'] = str_replace($replaceKeys,$replaceValues,$params['baseUrl']);
    $params['baseUrlRelative'] = !isset($params['baseUrlRelative']) || in_array($params['baseUrlRelative'],array('true',1,'1'));
}
$modxBasePath = $modx->getOption('base_path',null,MODX_BASE_PATH);
if ($params['basePathRelative'] && $modxBasePath != '/') {
    $params['basePath'] = ltrim(str_replace($modxBasePath,'',$params['basePath']),'/');
}
$modxBaseUrl = $modx->getOption('base_url',null,MODX_BASE_URL);
if ($params['baseUrlRelative'] && $modxBaseUrl != '/') {
    $params['baseUrl'] = ltrim(str_replace($modxBaseUrl,'',$params['baseUrl']),'/');
}

if (!empty($params['baseUrl']) && !empty($value)) {
    $relativeValue = $params['baseUrl'].ltrim($value,'/');
} else {
    $relativeValue = $value;
}

/* get auto-open to dir */
if (!empty($value) && strpos($value,'/') !== false) {
    $dir = pathinfo($value,PATHINFO_DIRNAME);
    $dir = rtrim($dir,'/').'/';
    $params['openTo'] = $dir;
}

$this->xpdo->smarty->assign('params',$params);

$this->set('relativeValue',$relativeValue);
$this->xpdo->smarty->assign('tv',$this);

// handles image fields using htmlarea image manager
$this->xpdo->smarty->assign('base_url',$this->xpdo->getOption('base_url'));
return $this->xpdo->smarty->fetch('element/tv/renders/input/file.tpl');