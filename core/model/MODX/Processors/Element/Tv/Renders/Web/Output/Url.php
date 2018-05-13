<?php

namespace MODX\Processors\Element\Tv\Renders\Web\Output;

use MODX\modResource;
use MODX\modTemplateVarOutputRender;

/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
class Url extends modTemplateVarOutputRender
{
    public function process($value, array $params = [])
    {
        if (empty($value)) return $value;

        $value = $this->tv->parseInput($value, "||", "array");
        $o = '';

        for ($i = 0; $i < count($value); $i++) {
            $s = is_array($value[$i]) ? $value[$i] : explode("==", $value[$i]);
            if (!isset($s[1])) $s[1] = $s[0];
            list($name, $url) = $s;

            if (!$url) $url = $name;
            if ((empty($name) || $name == $url) && $this->tv->get('type') == 'resourcelist') {
                /** @var modResource $resource */
                $resource = $this->modx->getObject('modResource', $url);
                if ($resource) {
                    $name = $resource->get('pagetitle');
                }
            }

            /* handle types that return IDs of resources */
            $rid = intval($url);
            if (!empty($rid)) {
                $url = '[[~' . $rid . ']]';
            }

            if ($url) {
                if ($o) $o .= '<br />';
                $attributes = '';
                /* setup the link attributes */
                $attr = [
                    'href' => $url,
                    'title' => !empty($params['title']) ? htmlspecialchars($params['title']) : $name,
                    'class' => !empty($params['class']) ? $params['class'] : null,
                    'style' => !empty($params['style']) ? $params['style'] : null,
                    'target' => !empty($params['target']) ? $params['target'] : null,
                ];
                foreach ($attr as $k => $v) $attributes .= ($v ? ' ' . $k . '="' . $v . '"' : '');
                if (!empty($params['attrib'])) {
                    $attributes .= ' ' . $params['attrib']; /* add extra */
                }

                /* Output the link */
                $o .= '<a' . rtrim($attributes) . '>' . (!empty($params['text']) ? htmlspecialchars($params['text'])
                        : $name) . '</a>';
            }
        }

        return $o;
    }
}