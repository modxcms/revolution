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
    list($item,$itemvalue) = (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
    if (strlen($itemvalue)==0) $itemvalue = $item;

    /* set checked status */
    if (in_array($itemvalue,$value)) {
        $checked = true;
    }
    /* add checkbox id to defaults if is a default value */
    if (in_array($itemvalue,$default)) {
        $defaults[] = 'tv'.$this->get('id').'-'.$i;
    }
    /* do escaping of strings, encapsulate in " so extjs/other systems can
     * utilize values correctly in their cast
     */
    if (intval($itemvalue) === 0 && $itemvalue !== 0 && $itemvalue !== '0') {
        $itemvalue = '"'.str_replace('"','\"',$itemvalue).'"';
    }

    $opts[] = array(
        'value' => $itemvalue,
        'text' => htmlspecialchars($item,ENT_COMPAT,'UTF-8'),
        'checked' => $checked,
    );
    $i++;
}
$this->xpdo->smarty->assign('cbdefaults',implode(',',$defaults));
$this->xpdo->smarty->assign('opts',$opts);
return $this->xpdo->smarty->fetch('element/tv/renders/input/checkbox.tpl');