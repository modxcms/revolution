<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$this->set('value',explode("||",$this->get('value')));
$index_list = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));
$opts = array();
while (list($item, $itemvalue) = each ($index_list)) {
    list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
    if (strlen($itemvalue)==0) $itemvalue = $item;
    $opts[] = array(
        'value' => htmlspecialchars($itemvalue,ENT_COMPAT,'UTF-8'),
        'text' => htmlspecialchars($item),
        'checked' => in_array($itemvalue,$this->get('value')),
    );
}

$this->xpdo->smarty->assign('opts',$opts);
return $this->xpdo->smarty->fetch('element/tv/renders/input/checkbox.tpl');