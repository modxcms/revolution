<?php
/**
 * @var modX $modx
 * @var modTemplateVar $this
 * @var array $params
 * 
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$modx->lexicon->load('tv_widget');

$modx->getService('fileHandler','modFileHandler', '', array('context' => $this->xpdo->context->get('key')));

/** @var modMediaSource $source */
$source = $this->getSource($modx->resource->get('context_key'));
if (!$source) return '';
if (!$source->getWorkingContext()) {
    return '';
}
$source->setRequestProperties($_REQUEST);
$source->initialize();
$modx->controller->setPlaceholder('source',$source->get('id'));

if (!$source->checkPolicy('view')) {
    $modx->controller->setPlaceholder('disabled',true);
    $this->set('disabled',true);
    $this->set('relativeValue',$this->get('value'));
} else {
    $value = $this->get('value');
    if (!empty($value)) {
        $params['openTo'] = $source->getOpenTo($value,$params);
    }
    $this->set('relativeValue',$value);
}

$modx->controller->setPlaceholder('params',$params);
$modx->controller->setPlaceholder('tv',$this);

return $modx->controller->fetchTemplate('element/tv/renders/input/file.tpl');