<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modTemplateVarInputRender;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderDate extends modTemplateVarInputRender
{
    public function process($value, array $params = [])
    {
        $v = $value;
        if ($v != '' && $v != '0' && $v != '0000-00-00 00:00:00') {
            $v = date('Y-m-d H:i:s', strtotime($v));
        }
        $this->tv->set('value', $v);

        if (!empty($params['disabledDates'])) {
            $params['disabledDates'] = $this->modx->toJSON(explode(',', $params['disabledDates']));
        }
        if (!empty($params['disabledDays'])) {
            $params['disabledDays'] = $this->modx->toJSON(explode(',', $params['disabledDays']));
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

return 'modTemplateVarInputRenderDate';
