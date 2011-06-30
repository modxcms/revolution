<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$which_editor = $this->xpdo->getOption('which_editor',null,'');
$this->xpdo->controller->setPlaceholder('which_editor',$which_editor);
return $this->xpdo->controller->fetchTemplate('element/tv/renders/input/richtext.tpl');