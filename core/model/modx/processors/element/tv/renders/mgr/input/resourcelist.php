<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$modx->lexicon->load('tv_widget');
$parents = $this->get('elements');

$bindingsResult = $this->processBindings($this->get('elements'),$modx->resource->get('id'));
$parents = $this->parseInputOptions($bindingsResult);
if (empty($parents)) { $parents = array($modx->getOption('site_start',null,1)); }

/* get all children */
$ids = array();
$oldContext = $modx->context->get('key');
$modx->switchContext($modx->resource->get('context_key'));
foreach ($parents as $parent) {
    $ids = array_merge($ids,$modx->getChildIds($parent));
}
$ids = array_unique($ids);
$modx->switchContext($oldContext);

/* get resources */
$c = $this->xpdo->newQuery('modResource');
$c->leftJoin('modResource','Parent');
$c->where(array(
    'modResource.id:IN' => $ids,
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