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
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
class modTemplateVarOutputRenderDelim extends modTemplateVarOutputRender {
    public function process($value,array $params = []) {
        $value= $this->tv->parseInput($value, "||");
        $p= isset($params['delimiter']) ? $params['delimiter'] : ',';

        if ($p == "\\n") $p= "\n";
        return str_replace("||", $p, $value);
    }
}
return 'modTemplateVarOutputRenderDelim';
