<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$index_list = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));
$opts = array();
while (list($item, $itemvalue) = each ($index_list)) {
    list($item,$itemvalue) = (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
    if (strlen($itemvalue)==0) $itemvalue = $item;
    $opts[] = array(
        'value' => htmlspecialchars($itemvalue),
        'text' => htmlspecialchars($item),
        'selected' => strcmp($itemvalue,$value) == 0,
    );
}

$this->xpdo->smarty->assign('opts',$opts);
return $this->xpdo->smarty->fetch('element/tv/renders/input/listbox-single.tpl');