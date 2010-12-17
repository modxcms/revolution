<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$modx->getService('fileHandler','modFileHandler', '', array('context' => $this->xpdo->context->get('key')));

$isRelativeBaseUrl = $this->xpdo->getOption('filemanager_path_relative',null,true);
if ($isRelativeBaseUrl) {
    $basePath = $this->xpdo->fileHandler->getBasePath(false);
    $baseUrl = $this->xpdo->fileHandler->getBaseUrl(true);
    $value = ltrim(str_replace($baseUrl,'',$this->get('value')),'/');
    $this->set('value',$value);
    $this->xpdo->smarty->assign('tv',$this);
}

// handles image fields using htmlarea image manager
$this->xpdo->smarty->assign('base_url',$this->xpdo->getOption('base_url'));
return $this->xpdo->smarty->fetch('element/tv/renders/input/image.tpl');