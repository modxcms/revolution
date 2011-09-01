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
if (!$source->getWorkingContext()) {
    return '';
}
$source->setRequestProperties($_REQUEST);
$source->initialize();
$modx->controller->setPlaceholder('source',$source->get('id'));
$properties = $source->getPropertyList();

$value = $this->get('value');

if (!empty($value)) {
    $params['openTo'] = $source->getOpenTo($value,$params);
}

$modx->controller->setPlaceholder('params',$params);

$this->set('relativeValue',$value);
$modx->controller->setPlaceholder('tv',$this);

$o = $modx->controller->fetchTemplate('element/tv/renders/input/image.tpl');
//var_dump($o);die();
return $o;