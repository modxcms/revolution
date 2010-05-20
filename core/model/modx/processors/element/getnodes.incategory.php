<?php
/**
 * @package modx
 */
$nodes = array();
/* 0: type,  1: element/category  2: elID  3: catID */
$categoryId = isset($g[3]) ? $g[3] : ($g[1] == 'category' ? $g[2] : 0);
$elementIdentifier = $g[0];
$elementType = ucfirst($elementIdentifier);
$elementClassKey = $ar_typemap[$elementIdentifier];

/* first handle subcategories */
$c = $modx->newQuery('modCategory');
$c->select($modx->getSelectColumns('modCategory','modCategory'));
$c->select('COUNT(`'.$elementClassKey.'`.`id`) AS `elementCount`');
$c->leftJoin($elementClassKey,$elementClassKey,'`'.$elementClassKey.'`.`category` = `modCategory`.`id`');
$c->where(array(
    'parent' => $categoryId,
));
$c->groupby('`modCategory`.`id`');
$c->sortby('`category`','ASC');
$categories = $modx->getCollection('modCategory',$c);

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

/* loop through categories */
foreach ($categories as $category) {
    if (!$category->checkPolicy('list')) continue;
    if ($category->get('elementCount') <= 0) continue;

    $nodes[] = array(
        'text' => strip_tags($category->get('category')) . ' (' . $category->get('id') . ')',
        'id' => 'n_'.$g[0].'_category_'.($category->get('id') != null ? $category->get('id') : 0),
        'pk' => $category->get('id'),
        'category' => $category->get('id'),
        'data' => $category->toArray(),
        'leaf' => false,
        'cls' => $class,
        'classKey' => 'modCategory',
        'elementType' => $elementType,
        'page' => '',
        'type' => $elementIdentifier,
    );
}

/* all elements in category */
$c = $modx->newQuery($elementClassKey);
$c->where(array(
    'category' => $categoryId
));
$c->sortby($elementIdentifier == 'template' ? 'templatename' : 'name','ASC');

$elements = $modx->getCollection($elementClassKey,$c);
foreach ($elements as $element) {
    if (!$element->checkPolicy('list')) continue;
    $name = $elementIdentifier == 'template' ? $element->get('templatename') : $element->get('name');

    $class = 'icon-'.$elementIdentifier;
    $class .= $modx->hasPermission('new_'.$elementIdentifier) ? ' pnew' : '';
    $class .= $modx->hasPermission('edit_'.$elementIdentifier) && $element->checkPolicy(array('save','view')) ? ' pedit' : '';
    $class .= $modx->hasPermission('delete_'.$elementIdentifier) && $element->checkPolicy('remove') ? ' pdelete' : '';
    $class .= $modx->hasPermission('new_category') ? ' pnewcat' : '';
    
    $nodes[] = array(
        'text' => strip_tags($name) . ' (' . $element->get('id') . ')',
        'id' => 'n_'.$elementIdentifier.'_element_'.$element->get('id').'_'.$element->get('category'),
        'pk' => $element->get('id'),
        'category' => $categoryId,
        'leaf' => 1,
        'name' => $name,
        'cls' => $class,
        'page' => 'index.php?a='.$ar_actionmap[$elementIdentifier].'&id='.$element->get('id'),
        'type' => $elementIdentifier,
        'elementType' => $elementType,
        'classKey' => $elementClassKey,
    );
}

return $nodes;