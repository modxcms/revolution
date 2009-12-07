<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$value = $this->get('value');

// handles radio buttons
$index_list = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));
$opts = array();
$default = '';
$i = 0;
while (list($item, $itemvalue) = each ($index_list)) {
    $checked = false;
    list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
    if (strlen($itemvalue)==0) $itemvalue = $item;

    if ($itemvalue == $value) {
        $checked = true;
        $default = 'tv'.$this->get('id').'-'.$i;
    }

    $opts[] = array(
        'value' => htmlspecialchars($itemvalue),
        'text' => htmlspecialchars($item),
        'checked' => $checked,
    );
    $i++;
}
$this->xpdo->smarty->assign('opts',$opts);
$this->xpdo->smarty->assign('cbdefaults',$default);
return $this->xpdo->smarty->fetch('element/tv/renders/input/radio.tpl');