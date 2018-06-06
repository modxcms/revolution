<?php

namespace MODX\Processors\Element\Tv\Renders\Mgr\Input;

use MODX\modTemplateVarInputRender;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class Date extends modTemplateVarInputRender
{
    public function process($value, array $params = [])
    {
        $v = $value;
        if ($v != '' && $v != '0' && $v != '0000-00-00 00:00:00') {
            $v = strftime('%Y-%m-%d %H:%M:%S', strtotime($v));
        }
        $this->tv->set('value', $v);

        if (!empty($params['disabledDates'])) {
            $params['disabledDates'] = json_encode(explode(',', $params['disabledDates']));
        }
        if (!empty($params['disabledDays'])) {
            $params['disabledDays'] = json_encode(explode(',', $params['disabledDays']));
        }
        if (!empty($params['maxTimeValue'])) {
            $params['maxTimeValue'] = date('g:i A', strtotime($params['maxTimeValue']));
        }
        if (!empty($params['minTimeValue'])) {
            $params['minTimeValue'] = date('g:i A', strtotime($params['minTimeValue']));
        }
        $this->setPlaceholder('params', $params);
        $this->setPlaceholder('tv', $this->tv);
    }


    public function getTemplate()
    {
        return 'element/tv/renders/input/date.tpl';
    }
}
