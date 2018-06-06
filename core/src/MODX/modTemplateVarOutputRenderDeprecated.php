<?php

namespace MODX;

/**
 * Backwards support for <2.2-style output renders
 *
 * @package modx
 */
class modTemplateVarOutputRenderDeprecated extends modTemplateVarOutputRender
{
    /** @var MODX $xpdo */
    public $xpdo;


    public function process($value, array $params = [])
    {
        $output = '';
        $modx =& $this->modx;
        $this->xpdo =& $this->modx;

        /* simulate hydration */
        $tvArray = $this->tv->toArray();
        foreach ($tvArray as $k => $v) {
            $this->$k = $v;
        }

        $name = $this->tv->get('name');
        $id = "tv$name";
        $format = $this->tv->get('display');
        $tvtype = $this->tv->get('type');
        if (empty($type)) $type = 'default';

        if (!empty($params['modx.renderFile']) && file_exists($params['modx.renderFile'])) {
            $output = include $params['modx.renderFile'];
        }

        return $output;
    }


    public function get($k)
    {
        return $this->tv->get($k);
    }


    public function set($k, $v)
    {
        return $this->tv->set($k, $v);
    }
}