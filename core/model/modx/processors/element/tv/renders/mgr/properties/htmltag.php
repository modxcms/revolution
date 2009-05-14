<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.properties
 */

$modx->smarty->assign('base_url',$modx->getOption('base_url'));
return $modx->smarty->fetch('element/tv/renders/properties/htmltag.tpl');