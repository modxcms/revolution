<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$value = explode("||",$value);

$default = explode("||",$this->get('default_text'));

$index_list = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));
$opts = array();
$defaults = array();
$i = 0;
while (list($item, $itemvalue) = each ($index_list)) {
    $checked = false;
    list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
    if (strlen($itemvalue)==0) $itemvalue = $item;

    if (in_array($itemvalue,$value)) {
        $checked = true;
    }
    if (in_array($itemvalue,$default)) {
        $defaults[] = 'tv'.$this->get('id').'-'.$i;
    }

    $opts[] = array(
        'value' => htmlspecialchars($itemvalue,ENT_COMPAT,'UTF-8'),
        'text' => htmlspecialchars($item,ENT_COMPAT,'UTF-8'),
        'checked' => $checked,
    );
    $i++;
}

$this->xpdo->smarty->assign('cbdefaults',implode(',',$defaults));
$this->xpdo->smarty->assign('opts',$opts);
return $this->xpdo->smarty->fetch('element/tv/renders/input/checkbox.tpl');