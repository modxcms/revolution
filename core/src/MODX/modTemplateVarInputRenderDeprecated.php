<?php

namespace MODX;

/**
 * Backwards support for <2.2-style input renders
 *
 * @package modx
 */
class modTemplateVarInputRenderDeprecated extends modTemplateVarInputRender
{
    /** @var MODX $xpdo */
    public $xpdo;


    public function process($value, array $params = [])
    {
        $this->setPlaceholder('tv', $this->tv);
        $this->setPlaceholder('id', $this->tv->get('id'));
        $this->setPlaceholder('ctx', isset($_REQUEST['ctx']) ? $_REQUEST['ctx'] : 'web');
        $this->setPlaceholder('params', $params);

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

        $output = '';
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