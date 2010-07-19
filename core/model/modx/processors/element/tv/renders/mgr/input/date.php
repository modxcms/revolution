<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$v = $value;
if ($v != '' && $v != '0' && $v != '0000-00-00 00:00:00') {
    $v = strftime('%Y-%m-%d %H:%M:%S',strtotime($v));
}
$this->set('value',$v);

return $this->xpdo->smarty->fetch('element/tv/renders/input/date.tpl');