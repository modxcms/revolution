<?php

namespace MODX\Processors\Element\Tv\Renders\Web\Output;

use MODX\modTemplateVarOutputRender;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
class Delim extends modTemplateVarOutputRender
{
    public function process($value, array $params = [])
    {
        $value = $this->tv->parseInput($value, "||");
        $p = isset($params['delimiter']) ? $params['delimiter'] : ',';

        if ($p == "\\n") $p = "\n";

        return str_replace("||", $p, $value);
    }
}