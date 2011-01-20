<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$modx->getService('fileHandler','modFileHandler', '', array('context' => $this->xpdo->context->get('key')));

$value = $this->get('value');

/* get base path based on either TV param or filemanager_path */
if (empty($params['basePath'])) {
    $params['basePath'] = $modx->fileHandler->getBasePath();
    $params['basePathRelative'] = $this->xpdo->getOption('filemanager_path_relative',null,true) ? 1 : 0;
} else {
    $params['basePathRelative'] = !isset($params['basePathRelative']) || in_array($params['basePathRelative'],array('true',1,'1'));
}
if (empty($params['baseUrl'])) {
    $params['baseUrl'] = $modx->fileHandler->getBaseUrl();
    $params['baseUrlRelative'] = $this->xpdo->getOption('filemanager_url_relative',null,true) ? 1 : 0;
} else {
    $params['baseUrlRelative'] = !isset($params['baseUrlRelative']) || in_array($params['baseUrlRelative'],array('true',1,'1'));
}

if (!empty($params['baseUrl']) && !empty($value)) {
    $relativeValue = $params['baseUrl'].ltrim($value,'/');
} else {
    $relativeValue = $value;
}

$this->xpdo->smarty->assign('params',$params);

$this->set('relativeValue',$relativeValue);
$this->xpdo->smarty->assign('tv',$this);

// handles image fields using htmlarea image manager
$this->xpdo->smarty->assign('base_url',$this->xpdo->getOption('base_url'));
return $this->xpdo->smarty->fetch('element/tv/renders/input/file.tpl');