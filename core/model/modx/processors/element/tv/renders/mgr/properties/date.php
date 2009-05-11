<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.properties
 */

$modx->smarty->assign('base_url',$this->xpdo->getOption('base_url'));
return $modx->smarty->fetch('element/tv/renders/properties/date.tpl');