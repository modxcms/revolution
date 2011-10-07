<?php
/**
 * @var modX $modx
 * @var modTemplateVar $this
 * 
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
$modx->lexicon->load('tv_widget');
$parents = $this->get('elements');

$bindingsResult = $this->processBindings($this->get('elements'),$modx->resource->get('id'));
$parents = $this->parseInputOptions($bindingsResult);
$parents = !empty($params['parents']) || $params['parents'] === '0' ? explode(',',$params['parents']) : $parents;
$params['depth'] = !empty($params['depth']) ? $params['depth'] : 10;
if (empty($parents) || (empty($parents[0]) && $parents[0] !== '0')) {
    $parents = array();
}
$parentList = array();
foreach ($parents as $parent) {
    /** @var modResource $parent */
    $parent = $modx->getObject('modResource',$parent);
    if ($parent) $parentList[] = $parent;
}

/* get all children */
$ids = array();
if (!empty($parentList)) {
    foreach ($parentList as $parent) {
        if (!empty($params['includeParent'])) $ids[] = $parent->get('id');
        $children = $modx->getChildIds($parent->get('id'),$params['depth'],array(
            'context' => $parent->get('context_key'),
        ));
        $ids = array_merge($ids,$children);
    }
    $ids = array_unique($ids);
}

$c = $modx->newQuery('modResource');
$c->leftJoin('modResource','Parent');
if (!empty($ids)) {
    $c->where(array('modResource.id:IN' => $ids));
} else if (!empty($parents) && $parents[0] == 0) {
    $c->where(array('modResource.parent' => 0));
}
if (!empty($params['where'])) {
    $params['where'] = $modx->fromJSON($params['where']);
    $c->where($params['where']);
}
$c->sortby('Parent.menuindex,modResource.menuindex','ASC');
if (!empty($params['limit'])) {
    $c->limit($params['limit']);
}
$resources = $modx->getCollection('modResource',$c);

/* iterate */
$opts = array();
if (!empty($params['showNone'])) {
    $opts[] = array('value' => '','text' => '-','selected' => $this->get('value') == '');
}
/** @var modResource $resource */
foreach ($resources as $resource) {
    $selected = $resource->get('id') == $this->get('value');
    $opts[] = array(
        'value' => $resource->get('id'),
        'text' => $resource->get('pagetitle').' ('.$resource->get('id').')',
        'selected' => $selected,
    );
}
$modx->controller->setPlaceholder('opts',$opts);
return $modx->controller->fetchTemplate('element/tv/renders/input/resourcelist.tpl');
