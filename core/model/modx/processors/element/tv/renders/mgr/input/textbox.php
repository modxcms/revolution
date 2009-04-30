<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

// handles image fields using htmlarea image manager
return $this->xpdo->smarty->fetch('element/tv/renders/input/textbox.tpl');