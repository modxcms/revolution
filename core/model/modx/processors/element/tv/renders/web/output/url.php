<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.output
 */
if (empty($value)) return $value;

$value= $this->parseInput($value, "||", "array");

for ($i = 0; $i < count($value); $i++) {
    list($name,$url) = is_array($value[$i]) ? $value[$i]: explode("==",$value[$i]);
    if (!$url) $url = $name;
    if ((empty($name) || $name == $url) && $this->get('type') == 'resourcelist') {
        $resource = $modx->getObject('modResource',$url);
        if ($resource) {
            $name = $resource->get('pagetitle');
        }
    }

    /* handle types that return IDs of resources */
    $rid =intval($url);
    if (!empty($rid)) { $url = '[[~'.$rid.']]'; }

    if ($url) {
        if($o) $o.='<br />';
        $attributes = '';
        /* setup the link attributes */
        $attr = array(
            'href' => $url,
            'title' => $params['title'] ? htmlspecialchars($params['title']) : $name,
            'class' => $params['class'],
            'style' => $params['style'],
            'target' => $params['target'],
        );
        foreach ($attr as $k => $v) $attributes .= ($v ? ' '.$k.'="'.$v.'"' : '');
        $attributes .= ' '.$params['attrib']; /* add extra */

        /* Output the link */
        $o .= '<a'.rtrim($attributes).'>'. ($params['text'] ? htmlspecialchars($params['text']) : $name) .'</a>';
    }
}

return $o;