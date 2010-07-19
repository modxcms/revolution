<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');

$value = explode(",",$value);

$default = explode("||",$this->get('default_text'));

$options = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));

$c = $this->xpdo->newQuery('modTemplateVarResource');
$c->where(array(
    'tmplvarid' => $this->get('id'),
));
$tags = $this->xpdo->getCollection('modTemplateVarResource',$c);
$options = array();
foreach ($tags as $tag) {
    $vs = explode(',',$tag->get('value'));
    $options = array_merge($options,$vs);
}
$options = array_unique($options);
$opts = array();
$defaults = array();
$i = 0;
foreach ($options as $tag) {
    $checked = false;

    if (in_array($tag,$value)) { $checked = true; }
    if (in_array($tag,$default)) {
        $defaults[] = 'tv'.$this->get('id').'-'.$i;
    }

    $opts[] = array(
        'value' => htmlspecialchars($tag,ENT_COMPAT,'UTF-8'),
        'checked' => $checked,
    );
    $i++;
}

$this->xpdo->smarty->assign('cbdefaults',implode(',',$defaults));
$this->xpdo->smarty->assign('opts',$opts);

return $this->xpdo->smarty->fetch('element/tv/renders/input/autotag.tpl');