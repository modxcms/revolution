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

if (!empty($params['disabledDates'])) {
    $params['disabledDates'] = $modx->toJSON(explode(',',$params['disabledDates']));
    $this->xpdo->smarty->assign('params',$params);
}
if (!empty($params['disabledDays'])) {
    $params['disabledDays'] = $modx->toJSON(explode(',',$params['disabledDays']));
    $this->xpdo->smarty->assign('params',$params);
}
if (!empty($params['maxTimeValue'])) {
    $params['maxTimeValue'] = date('g:i A',strtotime($params['maxTimeValue']));
    $this->xpdo->smarty->assign('params',$params);
}
if (!empty($params['minTimeValue'])) {
    $params['minTimeValue'] = date('g:i A',strtotime($params['minTimeValue']));
    $this->xpdo->smarty->assign('params',$params);
}

return $this->xpdo->smarty->fetch('element/tv/renders/input/date.tpl');