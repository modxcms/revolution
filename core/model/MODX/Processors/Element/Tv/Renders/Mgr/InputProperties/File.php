<?php

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.inputproperties
 */

$modx->controller->setPlaceholder('base_url', $modx->getOption('base_url'));

return $modx->controller->fetchTemplate('element/tv/renders/inputproperties/file.tpl');