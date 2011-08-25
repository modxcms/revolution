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
$source = $this->getSource();
if (!$source->getWorkingContext()) {
    return '';
}
$source->setRequestProperties($_REQUEST);
$source->initialize();
$modx->controller->setPlaceholder('source',$source->get('id'));

$value = $this->get('value');

if ($source->get('baseUrl') && !empty($value)) {
    $relativeValue = $source->get('baseUrl').ltrim($value,'/');
} else {
    $relativeValue = $value;
}
if (!empty($value) && strpos($value,'/') !== false) {
    $dir = pathinfo($value,PATHINFO_DIRNAME);
    $dir = rtrim($dir,'/').'/';
    $params['openTo'] = $dir;
}

$modx->controller->setPlaceholder('params',$params);

$this->set('relativeValue',$relativeValue);
$modx->controller->setPlaceholder('tv',$this);

return $modx->controller->fetchTemplate('element/tv/renders/input/image.tpl');