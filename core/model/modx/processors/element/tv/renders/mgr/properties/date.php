<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.properties
 */

$modx->controller->setPlaceholder('base_url',$modx->getOption('base_url'));
return $modx->controller->fetchTemplate('element/tv/renders/properties/date.tpl');