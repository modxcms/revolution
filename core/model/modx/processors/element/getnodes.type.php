<?php
/**
 * @package modx
 * @subpackage processors.element
 */
$elementType = ucfirst($g[1]);
$elementClassKey = $ar_typemap[$g[1]];

/* get elements in this type */
$c = $modx->newQuery('modCategory');
$c->select('modCategory.*,
    COUNT(`'.$elementClassKey.'`.`id`) AS elementCount,
    COUNT(`Children`.`id`) AS childrenCount
');
$c->leftJoin($elementClassKey,$elementClassKey,'`'.$elementClassKey.'`.`category` = `modCategory`.`id`');
$c->leftJoin('modCategory','Children');
$c->where(array(
    'modCategory.parent' => 0,
));
$c->sortby('`category`','ASC');
$c->groupby('`modCategory`.`id`');
$categories = $modx->getCollection('modCategory',$c);

/* build category menu */
$categoryMenu = array();
$categoryMenu[] = '-';
if ($modx->hasPermission('new_'.$g[1])) {
    $categoryMenu[] = array(
        'text' => $modx->lexicon('add_to_category_this',array('type' => $elementType)),
        'handler' => 'function(itm,e) {
            this._createElement(itm,e);
        }',
    );
}
$categoryMenu[] = $quickCreateMenu;
$categoryMenu[] = '-';
if ($modx->hasPermission('new_category')) {
    $categoryMenu[] = array(
        'text' => $modx->lexicon('category_create'),
        'handler' => 'function(itm,e) {
            this.createCategory(itm,e);
        }',
    );
}
if ($modx->hasPermission('delete_category')) {
    $categoryMenu[] = array(
        'text' => $modx->lexicon('remove_category'),
        'handler' => 'function(itm,e) {
            this.removeCategory(itm,e);
        }',
    );
}

/* loop through categories with elements in this type */
foreach ($categories as $category) {
    if ($category->get('elementCount') == 0 && $category->get('childrenCount') <= 0 && $category->get('id') != 0) {
        continue;
    }

    array_unshift($categoryMenu,array(
        'text' => '<b>'.$category->get('category').'</b>',
        'params' => '',
        'handler' => 'function() { return false; }',
        'header' => true,
    ));
    $nodes[] = array(
        'text' => $category->get('category') . ' (' . $category->get('id') . ')',
        'id' => 'n_'.$g[1].'_category_'.($category->get('id') != null ? $category->get('id') : 0),
        'pk' => $category->get('id'),
        'category' => $category->get('id'),
        'data' => $category->toArray(),
        'leaf' => false,
        'cls' => 'icon-category',
        'page' => '',
        'type' => $g[1],
        'menu' => array(
            'items' => $categoryMenu,
        ),
    );
    array_shift($categoryMenu);
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
    /* handle templatename case */
    $name = $elementClassKey == 'modTemplate' ? $element->get('templatename') : $element->get('name');

    $menu = array();
    $menu[] = array(
        'text' => '<b>'.$name.'</b>',
        'params' => '',
        'handler' => 'function() { return false; }',
        'header' => true,
    );
    $menu[] = '-';
    if ($modx->hasPermission('edit_'.$g[1])) {
        $menu[] = array(
            'text' => $modx->lexicon('edit').' '.$elementType,
            'handler' => 'function() {
                location.href = "index.php?'
                    . 'a=' . $actions['element/'.strtolower($elementType).'/update']
                    . '&id=' . $element->get('id')
                 . '";
            }',
        );
    }
    if ($modx->hasPermission('edit_'.$g[1])) {
        $menu[] = array(
            'text' => $modx->lexicon('quick_update_'.$g[1]),
            'handler' => 'function(itm,e) { this.quickUpdate(itm,e,"'.$g[1].'"); }',
        );
    }
    if ($modx->hasPermission('new_'.$g[1])) {
        $menu[] = array(
            'text' => $modx->lexicon('duplicate').' '.$elementType,
            'handler' => 'function(itm,e) {
                this.duplicateElement(itm,e,'.$element->get('id').',"'.strtolower($elementType).'");
            }',
        );
    }
    if ($modx->hasPermission('delete_'.$g[1])) {
        $menu[] = array(
            'text' => $modx->lexicon('remove').' '.$elementType,
            'handler' => 'function(itm,e) { this.removeElement(itm,e); }',
        );
    }
    $menu[] = '-';
    if ($modx->hasPermission('new_'.$g[1])) {
        $menu[] = array(
            'text' => $modx->lexicon('add_to_category_this',array('type' => $elementType)),
            'handler' => 'function(itm,e) { this._createElement(itm,e); }',
        );
    }

    if ($modx->hasPermission('new_category')) {
        $menu[] = array(
            'text' => $modx->lexicon('new_category'),
            'handler' => 'function(itm,e) { this.createCategory(itm,e); }',
        );
    }

    $nodes[] = array(
        'text' => $name . ' (' . $element->get('id') . ')',
        'id' => 'n_'.$g[1].'_element_'.$element->get('id').'_0',
        'pk' => $element->get('id'),
        'category' => 0,
        'leaf' => true,
        'name' => $name,
        'cls' => 'icon-'.$g[1],
        'page' => '?a='.$ar_actionmap[$g[1]].'&id='.$element->get('id'),
        'type' => $g[1],
        'classKey' => $elementClassKey,
        'qtip' => $element->get('description'),
        'menu' => array(
            'items' => $menu,
        ),
    );
}
return $nodes;