<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$index_list = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));
$items = array();
while (list($item, $itemvalue) = each ($index_list)) {
    list($item,$itemvalue) = (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
    if (strlen($itemvalue)==0) $itemvalue = $item;
    $items[] = array(
        'text' => htmlspecialchars($item,ENT_COMPAT,'UTF-8'),
        'value' => htmlspecialchars($itemvalue,ENT_COMPAT,'UTF-8'),
        'selected' => strcmp($itemvalue,$value) == 0,
    );
}

$this->xpdo->smarty->assign('tvitems',$items);
return $this->xpdo->smarty->fetch('element/tv/renders/input/dropdown.tpl');