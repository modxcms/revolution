<?php
/**
 * @var string|array $value
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$value = is_array($value) ? $value : explode(',',$value);

$default = explode('||',$this->get('default_text'));

$options = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));
$opts = array();
$defaults = array();
$i = 0;

while (list($item, $itemValue) = each ($options)) {
    $checked = false;
    $itemValue = is_array($itemValue) ? $itemValue : explode('==',$itemValue);
    $item = $itemValue[0];
    $itemValue = isset($itemValue[1]) ? $itemValue : $item;
    if (in_array($itemValue,$value)) {
        $checked = true;
    }
    if (in_array($itemValue,$default)) {
        $defaults[] = 'tv'.$this->get('id').'-'.$i;
    }

    $opts[] = array(
        'value' => htmlspecialchars($itemValue,ENT_COMPAT,'UTF-8'),
        'text' => htmlspecialchars($item,ENT_COMPAT,'UTF-8'),
        'checked' => $checked,
    );
    $i++;
}

$this->xpdo->controller->setPlaceholder('cbdefaults',implode(',',$defaults));
$this->xpdo->controller->setPlaceholder('opts',$opts);

return $this->xpdo->controller->fetchTemplate('element/tv/renders/input/tag.tpl');