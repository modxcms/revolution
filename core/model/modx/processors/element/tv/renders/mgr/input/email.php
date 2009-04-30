<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

return $this->xpdo->smarty->fetch('element/tv/renders/input/textbox.tpl');