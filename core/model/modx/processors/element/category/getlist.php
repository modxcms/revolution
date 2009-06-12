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

if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

$limit = !empty($_REQUEST['limit']);
$showNone = !empty($_REQUEST['showNone']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'parent,category';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modCategory');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->where(array(
    'parent' => 0,
));
if ($limit) {
	$c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$categories = $modx->getCollection('modCategory',$c);

if ($showNone) {
    $cs = array('0' => array(
        'id' => '',
        'category' => $modx->lexicon('none'),
        'name' => $modx->lexicon('none'),
    ));
}

foreach ($categories as $category) {
    $ca = $category->toArray();

    $childrenCount = $modx->getCount('modCategory',array('parent' => $category->get('id')));

    $ca['name'] = $category->get('category');
	$cs[] = $ca;

    if ($childrenCount > 0) {
        $c = $modx->newQuery('modCategory');
        $c->where(array('parent' => $category->get('id')));
        $c->sortby('category','ASC');
        $children = $category->getMany('Children',$c);
        foreach ($children as $subcat) {
            $ca = $subcat->toArray();
            $ca['name'] = $category->get('category').' - '.$subcat->get('category');
            $cs[] = $ca;
        }
    }
}

return $this->outputArray($cs);