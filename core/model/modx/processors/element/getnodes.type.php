<?php
/**
 * @package modx
 * @subpackage processors.element
 */
$elementType = ucfirst($g[1]);
$elementClassKey = $ar_typemap[$g[1]];

/* get elements in this type */
$c = $modx->newQuery('modCategory');
$c->select($modx->getSelectColumns('modCategory','modCategory'));
$c->select('
    COUNT('.$modx->getSelectColumns($elementClassKey,$elementClassKey,'',array('id')).') AS `elementCount`,
    COUNT('.$modx->getSelectColumns('modCategory','Children','',array('id')).') AS `childrenCount`
');
$c->leftJoin($elementClassKey,$elementClassKey,$modx->getSelectColumns($elementClassKey,$elementClassKey,'',array('category')).' = '.$modx->getSelectColumns('modCategory','modCategory','',array('id')));
$c->leftJoin('modCategory','Children');
$c->where(array(
    'modCategory.parent' => 0,
));
$c->sortby($modx->getSelectColumns('modCategory','modCategory','',array('category')),'ASC');
$c->groupby($modx->getSelectColumns('modCategory','modCategory','',array('id')));
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

/* loop through categories with elements in this type */
foreach ($categories as $category) {
    if (!$category->checkPolicy('list')) continue;
    if ($category->get('elementCount') == 0 && $category->get('childrenCount') <= 0 && $category->get('id') != 0) {
        continue;
    }

    $nodes[] = array(
        'text' => strip_tags($category->get('category')) . ' (' . $category->get('id') . ')',
        'id' => 'n_'.$g[1].'_category_'.($category->get('id') != null ? $category->get('id') : 0),
        'pk' => $category->get('id'),
        'category' => $category->get('id'),
        'data' => $category->toArray(),
        'leaf' => false,
        'cls' => $class,
        'page' => '',
        'classKey' => 'modCategory',
        'elementType' => $elementType,
        'type' => $g[1],
    );
    unset($elCount,$childCats);
}

/* now add elements in this type without a category */
$c = $modx->newQuery($elementClassKey);
$c->where(array(
    'category' => 0,
));
$c->sortby($elementClassKey == 'modTemplate' ? 'templatename' : 'name','ASC');
$elements = $modx->getCollection($elementClassKey,$c);

/* loop through elements */
foreach ($elements as $element) {
    if (!$element->checkPolicy('list')) continue;
    /* handle templatename case */
    $name = $elementClassKey == 'modTemplate' ? $element->get('templatename') : $element->get('name');

    $class = 'icon-'.$g[1];
    $class .= $modx->hasPermission('new_'.$g[1]) ? ' pnew' : '';
    $class .= $modx->hasPermission('edit_'.$g[1]) && $element->checkPolicy(array('save','view')) ? ' pedit' : '';
    $class .= $modx->hasPermission('delete_'.$g[1]) && $element->checkPolicy('remove') ? ' pdelete' : '';
    $class .= $modx->hasPermission('new_category') ? ' pnewcat' : '';

    $nodes[] = array(
        'text' => strip_tags($name) . ' (' . $element->get('id') . ')',
        'id' => 'n_'.$g[1].'_element_'.$element->get('id').'_0',
        'pk' => $element->get('id'),
        'category' => 0,
        'leaf' => true,
        'name' => $name,
        'cls' => $class,
        'page' => '?a='.$ar_actionmap[$g[1]].'&id='.$element->get('id'),
        'type' => $g[1],
        'elementType' => $elementType,
        'classKey' => $elementClassKey,
        'qtip' => strip_tags($element->get('description')),
    );
}
return $nodes;