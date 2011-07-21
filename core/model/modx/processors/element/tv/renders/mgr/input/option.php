<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');
$default = $this->get('default_text');

// handles radio buttons
$options = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));
$items = array();
$defaultIndex = '';
$i = 0;
foreach ($options as $option) {
    $opt = explode("==",$option);
    if (!isset($opt[1])) $opt[1] = $opt[0];
    $checked = FALSE;

    /* set checked status */
    if (strcmp($opt[1],$value) == 0) {
        $checked = TRUE;
    }
    /* set default value */
    if (strcmp($opt[1],$default) == 0) {
        $defaultIndex = 'tv'.$this->get('id').'-'.$i;
        $this->set('default_text',$defaultIndex);
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
$this->xpdo->smarty->assign('opts',$items);
$this->xpdo->smarty->assign('cbdefaults',$defaultIndex);
return $this->xpdo->smarty->fetch('element/tv/renders/input/radio.tpl');
