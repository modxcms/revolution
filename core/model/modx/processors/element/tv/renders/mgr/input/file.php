<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$modx->lexicon->load('tv_widget');

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
    $workingContext =& $modx->context;
}


$value = $this->get('value');

/* get base path based on either TV param or filemanager_path */
$replacePaths = array(
    '[[++base_path]]' => $workingContext->getOption('base_path',MODX_BASE_PATH),
    '[[++core_path]]' => $workingContext->getOption('core_path',MODX_CORE_PATH),
    '[[++manager_path]]' => $workingContext->getOption('manager_path',MODX_MANAGER_PATH),
    '[[++assets_path]]' => $workingContext->getOption('assets_path',MODX_ASSETS_PATH),
    '[[++base_url]]' => $workingContext->getOption('base_url',MODX_BASE_URL),
    '[[++manager_url]]' => $workingContext->getOption('manager_url',MODX_MANAGER_URL),
    '[[++assets_url]]' => $workingContext->getOption('assets_url',MODX_ASSETS_URL),
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
$modxBasePath = $workingContext->getOption('base_path',MODX_BASE_PATH);
if ($params['basePathRelative'] && $modxBasePath != '/') {
    $params['basePath'] = ltrim(str_replace($modxBasePath,'',$params['basePath']),'/');
}
$modxBaseUrl = $workingContext->getOption('base_url',MODX_BASE_URL);
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

$modx->controller->setPlaceholder('params',$params);

$this->set('relativeValue',$relativeValue);
$modx->controller->setPlaceholder('tv',$this);

// handles image fields using htmlarea image manager
$modx->controller->setPlaceholder('base_url',$workingContext->getOption('base_url'));
return $modx->controller->fetchTemplate('element/tv/renders/input/file.tpl');