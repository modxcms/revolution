<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$value = explode("||",$value);

$options = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));
$items = array();
foreach ($options as $option) {
    $opt = explode("==",$option);
    if (!isset($opt[1])) $opt[1] = $opt[0];
    $items[] = array(
        'text' => htmlspecialchars($opt[0],ENT_COMPAT,'UTF-8'),
        'value' => htmlspecialchars($opt[1],ENT_COMPAT,'UTF-8'),
        'selected' => in_array($opt[1],$value),
    );
}

$this->xpdo->controller->setPlaceholder('opts',$items);
return $this->xpdo->controller->fetchTemplate('element/tv/renders/input/listbox-multiple.tpl');