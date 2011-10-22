<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$value = explode("||",$value);

$default = explode("||",$this->get('default_text'));

$options = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));

$items = array();
$defaults = array();
$i = 0;
foreach ($options as $option) {
    $opt = explode("==",$option);
    $checked = false;
    if (!isset($opt[1])) $opt[1] = $opt[0];

    /* set checked status */
    if (in_array($opt[1],$value)) {
        $checked = true;
    }
    /* add checkbox id to defaults if is a default value */
    if (in_array($opt[1],$default)) {
        $defaults[] = 'tv'.$this->get('id').'-'.$i;
    }
    /* do escaping of strings, encapsulate in " so extjs/other systems can
     * utilize values correctly in their cast
     */
    if (preg_match('/^([-]?(0|0{1}[1-9]+[0-9]*|[1-9]+[0-9]*[\.]?[0-9]*))$/',$opt[1]) == 0) {
        $opt[1] = '"'.str_replace('"','\"',$opt[1]).'"';
    }

    $items[] = array(
        'text' => htmlspecialchars($opt[0],ENT_COMPAT,'UTF-8'),
        'value' => $opt[1],
        'checked' => $checked,
    );
    $i++;
}
$this->xpdo->controller->setPlaceholder('cbdefaults',implode(',',$defaults));
$this->xpdo->controller->setPlaceholder('opts',$items);
return $this->xpdo->controller->fetchTemplate('element/tv/renders/input/checkbox.tpl');