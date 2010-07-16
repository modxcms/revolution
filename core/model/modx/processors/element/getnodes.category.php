<?php
/**
 * @package modx
 * @subpackage processors.element
 */
if (!empty($g[1])) {
    /* if grabbing subcategories */
    $c = $modx->newQuery('modCategory');
    $c->where(array(
        'parent' => $g[1],
    ));
    $c->sortby($modx->getSelectColumns('modCategory','modCategory','',array('category')),'ASC');
} else {
    /* if trying to grab all root categories */
    $c = $modx->newQuery('modCategory');
    $c->where(array(
        'parent' => 0,
    ));
    $c->sortby($modx->getSelectColumns('modCategory','modCategory','',array('category')),'ASC');
}

$c->select($modx->getSelectColumns('modCategory','modCategory'));
$c->select(array(
    'COUNT('.$modx->getSelectColumns('modCategory','Children','',array('id')).') AS `childrenCount`',
));
$c->leftJoin('modCategory','Children');
$c->groupby($modx->getSelectColumns('modCategory','modCategory','',array('id')));

/* set permissions as css classes */
$class = 'icon-category folder';
$types = array('template','tv','chunk','snippet','plugin');
foreach ($types as $type) {
    if ($modx->hasPermission('new_'.$type)) {
        $class .= ' pnew_'.$type;
    }
}
$class .= $modx->hasPermission('new_category') ? ' pnewcat' : '';
$class .= $modx->hasPermission('edit_category') ? ' peditcat' : '';
$class .= $modx->hasPermission('delete_category') ? ' pdelcat' : '';

/* get and loop through categories */
$categories = $modx->getCollection('modCategory',$c);
foreach ($categories as $category) {
    if (!$category->checkPolicy('list')) continue;
    $nodes[] = array(
        'text' => strip_tags($category->get('category')) . ' (' . $category->get('id') . ')',
        'id' => 'n_category_'.$category->get('id'),
        'pk' => $category->get('id'),
        'data' => $category->toArray(),
        'category' => $category->get('id'),
        'leaf' => false,
        'cls' => $class,
        'page' => '',
        'classKey' => 'modCategory',
        'type' => 'category',
    );
}

return $nodes;