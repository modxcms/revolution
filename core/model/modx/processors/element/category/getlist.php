<?php
/**
 * Grabs a list of modCategories.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to category.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.category
 */
if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('category');

/* setup default properties */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
$sort = $modx->getOption('sort',$_REQUEST,'parent,category');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$showNone = $modx->getOption('showNone',$_REQUEST,false);

/* query for categories */
$c = $modx->newQuery('modCategory');
$c->sortby($sort,$dir);
$c->where(array(
    'parent' => 0,
));
if ($isLimit) $c->limit($limit,$start);
$categories = $modx->getCollection('modCategory',$c);

$list = array();
if ($showNone) {
    $list = array('0' => array(
        'id' => '',
        'category' => $modx->lexicon('none'),
        'name' => $modx->lexicon('none'),
    ));
}

/* iterate through categories */
foreach ($categories as $category) {
    $ca = $category->toArray();

    $childrenCount = $modx->getCount('modCategory',array('parent' => $category->get('id')));

    $ca['name'] = $category->get('category');
	$list[] = $ca;

    if ($childrenCount > 0) {
        $c = $modx->newQuery('modCategory');
        $c->where(array('parent' => $category->get('id')));
        $c->sortby('category','ASC');
        $children = $category->getMany('Children',$c);
        foreach ($children as $subcat) {
            $ca = $subcat->toArray();
            $ca['name'] = $category->get('category').' - '.$subcat->get('category');
            $list[] = $ca;
        }
    }
}

return $this->outputArray($list);