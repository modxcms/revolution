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

$parentList = array();
foreach ($parents as $parent) {
    $parent = $modx->getObject('modResource',$parent);
    if ($parent) $parentList[] = $parent;
}

/* get all children */
$ids = array();
$oldContext = $modx->context->get('key');
$currentContext = '';
foreach ($parentList as $parent) {
    if ($parent->get('context_key') != $currentContext) {
        $modx->switchContext($parent->get('context_key'));
        $currentContext = $parent->get('context_key');
    }
    $ids = array_merge($ids,$modx->getChildIds($parent->get('id')));
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
    $selected = $resource->get('id') == $this->get('value');
    $opts[] = array(
        'value' => $resource->get('id'),
        'text' => $resource->get('pagetitle').' ('.$resource->get('id').')',
        'selected' => $selected,
    );
}
$this->xpdo->smarty->assign('tvitems',$opts);
return $this->xpdo->smarty->fetch('element/tv/renders/input/dropdown.tpl');