<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');
$default = $this->get('default_text');

// handles radio buttons
$index_list = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));
$opts = array();
$defaultIndex = '';
$i = 0;
while (list($item, $itemvalue) = each ($index_list)) {
    $checked = false;
    list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
    if (strlen($itemvalue)==0) $itemvalue = $item;

    if (strcmp($itemvalue,$value) == 0) {
        $checked = true;
    }
    if (strcmp($itemvalue,$default) == 0) {
        $defaultIndex = 'tv'.$this->get('id').'-'.$i;
        $this->set('default_text',$defaultIndex);
    }

    $opts[] = array(
        'value' => htmlspecialchars($itemvalue,ENT_COMPAT,'UTF-8'),
        'text' => htmlspecialchars($item,ENT_COMPAT,'UTF-8'),
        'checked' => $checked,
    );
    $i++;
}
$this->xpdo->smarty->assign('opts',$opts);
$this->xpdo->smarty->assign('cbdefaults',$defaultIndex);
return $this->xpdo->smarty->fetch('element/tv/renders/input/radio.tpl');