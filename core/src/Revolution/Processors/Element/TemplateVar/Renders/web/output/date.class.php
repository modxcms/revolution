<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

use MODX\Revolution\modTemplateVarOutputRender;

/**
 * @var modX $modx
 * @var array $params
 * @var string $value
 *
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
class modTemplateVarOutputRenderDate extends modTemplateVarOutputRender {
    public function process($value,array $params = []) {
        /* default properties */
        $params['format'] = !empty($params['format']) ? $params['format'] : "%A %d, %B %Y";
        /* fix for 2.0.0-pl bug where 1=yes and 0=no */
        $params['default'] = !empty($params['default']) && in_array($params['default'], ['yes',1,'1']) ? 1 : 0;

        $value= $this->tv->parseInput($value);

        /* if not using current time and no value, return */
        if (empty($value) && empty($params['default'])) return '';

        /* if using current, and value empty, get current time */
        if (!empty($params['default']) && empty($value)) {
            $timestamp = time();
        } else { /* otherwise get timestamp */
            $timestamp= strtotime($value);
        }

        /* return formatted time */
        return strftime($params['format'],$timestamp);
    }
}
return 'modTemplateVarOutputRenderDate';
