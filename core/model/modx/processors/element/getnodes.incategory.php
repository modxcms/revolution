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
$c->select('COUNT('.$elementClassKey.'.id) AS elementCount');
$c->leftJoin($elementClassKey,$elementClassKey,$elementClassKey.'.category = modCategory.id');
$c->where(array(
    'parent' => $categoryId,
));
$c->groupby($modx->getSelectColumns('modCategory','modCategory'));
$c->sortby($modx->getSelectColumns('modCategory','modCategory','',array('category')),'ASC');
$categories = $modx->getCollection('modCategory',$c);

/* set permissions as css classes */
$class = array('icon-category','folder');
$types = array('template','tv','chunk','snippet','plugin');
foreach ($types as $type) {
    if ($modx->hasPermission('new_'.$type)) {
        $class[] = 'pnew_'.$type;
    }
}
if ($modx->hasPermission('new_category')) $class[] = 'pnewcat';
if ($modx->hasPermission('edit_category')) $class[] = 'peditcat';
if ($modx->hasPermission('delete_category')) $class[] = 'pdelcat';
$class = implode(' ',$class);

/* loop through categories */
foreach ($categories as $category) {
    if (!$category->checkPolicy('list')) continue;
    if ($category->get('elementCount') <= 0) continue;

    $nodes[] = array(
        'text' => strip_tags($category->get('category')) . ' (' . $category->get('elementCount') . ')',
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

/* do permission checks */
$canNewElement = $modx->hasPermission('new_'.$elementIdentifier);
$canEditElement = $modx->hasPermission('edit_'.$elementIdentifier);
$canDeleteElement = $modx->hasPermission('delete_'.$elementIdentifier);
$canNewCategory = $modx->hasPermission('new_category');
$showElementIds = $modx->hasPermission('tree_show_element_ids');

/* loop through elements */
foreach ($elements as $element) {
    if (!$element->checkPolicy('list')) continue;
    $name = $elementIdentifier == 'template' ? $element->get('templatename') : $element->get('name');

    $class = array('icon-'.$elementIdentifier);
    if ($canNewElement) $class[] = 'pnew';
    if ($canEditElement && $element->checkPolicy(array('save' => true, 'view' => true))) $class[] = 'pedit';
    if ($canDeleteElement && $element->checkPolicy('remove')) $class[] = 'pdelete';
    if ($canNewCategory) $class[] = 'pnewcat';
    if ($element->get('locked')) $class[] = 'element-node-locked';
    if ($elementClassKey == 'modPlugin' && $element->get('disabled')) {
        $class[] = 'element-node-disabled';
    }

    $idNote = $showElementIds ? ' (' . $element->get('id') . ')' : '';
    $nodes[] = array(
        'text' => strip_tags($name) . $idNote,
        'id' => 'n_'.$elementIdentifier.'_element_'.$element->get('id').'_'.$element->get('category'),
        'pk' => $element->get('id'),
        'category' => $categoryId,
        'leaf' => 1,
        'name' => $name,
        'cls' => implode(' ',$class),
        'page' => 'index.php?a='.$ar_actionmap[$elementIdentifier].'&id='.$element->get('id'),
        'type' => $elementIdentifier,
        'elementType' => $elementType,
        'classKey' => $elementClassKey,
        'qtip' => strip_tags($element->get('description')),
    );
}

return $nodes;