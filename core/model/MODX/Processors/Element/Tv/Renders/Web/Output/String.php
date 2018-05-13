<?php

namespace MODX\Processors\Element\Tv\Renders\Web\Output;

use MODX\modTemplateVarOutputRender;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
class String extends modTemplateVarOutputRender
{
    public function process($value, array $params = [])
    {
        $value = $this->tv->parseInput($value);
        $format = !empty($params['format']) ? strtolower($params['format']) : '';
        switch ($format) {
            case 'upper case':
                $o = strtoupper($value);
                break;
            case 'lower case':
                $o = strtolower($value);
                break;
            case 'sentence case':
                $o = ucfirst($value);
                break;
            case 'capitalize':
                $o = ucwords($value);
                break;
            default:
                $o = $value;
                break;
        }

        return $o;
    }
}