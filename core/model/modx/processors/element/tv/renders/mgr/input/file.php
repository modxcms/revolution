<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');
$this->xpdo->smarty->assign('base_url',$this->xpdo->getOption('base_url'));

return $this->xpdo->smarty->fetch('element/tv/renders/input/file.tpl');