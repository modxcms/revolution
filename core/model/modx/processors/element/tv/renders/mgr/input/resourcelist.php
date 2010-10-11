<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$this->xpdo->lexicon->load('tv_widget');
$parents = $this->get('elements');

$bindingsResult = $this->processBindings($this->get('elements'),$this->xpdo->resource->get('id'));
$parent = $this->parseInputOptions($bindingsResult);
if (empty($parent)) { $parent = array($this->xpdo->getOption('site_start',null,1)); }

/* get parents */
$parent = explode(',',$parent[0]);
$c = $this->xpdo->newQuery('modResource');
$c->leftJoin('modResource','Parent');
$c->where(array(
    'modResource.parent:IN' => $parent,
));
$c->sortby('Parent.menuindex,modResource.menuindex','ASC');
$resources = $this->xpdo->getCollection('modResource',$c);

/* iterate */
$opts = array();
foreach ($resources as $resource) {
    $checked = $resource->get('id') == (int)$itemvalue;
    $opts[] = array(
        'value' => $resource->get('id'),
        'text' => $resource->get('pagetitle').' ('.$resource->get('id').')',
        'checked' => $checked,
    );
}
$this->xpdo->smarty->assign('tvitems',$opts);
return $this->xpdo->smarty->fetch('element/tv/renders/input/dropdown.tpl');