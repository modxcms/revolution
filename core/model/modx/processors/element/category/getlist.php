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
$modx->lexicon->load('category');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'parent,category');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$showNone = $modx->getOption('showNone',$scriptProperties,false);

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
    if (!$category->checkPolicy('list')) continue;
    $categoryArray = $category->toArray();

    $childrenCount = $modx->getCount('modCategory',array('parent' => $category->get('id')));

    $categoryArray['name'] = $category->get('category');
	$list[] = $categoryArray;

    /* if has subcategories, display here */
    if ($childrenCount > 0) {
        $c = $modx->newQuery('modCategory');
        $c->where(array('parent' => $category->get('id')));
        $c->sortby('category','ASC');
        $children = $category->getMany('Children',$c);
        foreach ($children as $subcat) {
            $categoryArray = $subcat->toArray();
            $categoryArray['name'] = $category->get('category').' - '.$subcat->get('category');
            $list[] = $categoryArray;
        }
    }
}

return $this->outputArray($list);