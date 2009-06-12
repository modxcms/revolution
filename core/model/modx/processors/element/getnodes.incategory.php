<?php
/**
 * @package modx
 */
$nodes = array();
/* 0: type,  1: element/category  2: elID  3: catID */
$categoryId = isset($g[3]) ? $g[3] : ($g[1] == 'category' ? $g[2] : 0);
$elementIdentifer = $g[0];
$elementType = ucfirst($elementIdentifer);
$elementClassKey = $ar_typemap[$elementIdentifer];

/* first handle subcategories */
$c = $modx->newQuery('modCategory');
$c->select('modCategory.*,
    COUNT(`'.$elementClassKey.'`.`id`) AS elementCount
');
$c->leftJoin($elementClassKey,$elementClassKey,'`'.$elementClassKey.'`.`category` = `modCategory`.`id`');
$c->where(array(
    'parent' => $categoryId,
));
$c->groupby('`modCategory`.`id`');
$c->sortby('`category`','ASC');
$categories = $modx->getCollection('modCategory',$c);

/* setup category menu */
$categoryMenu = array();
$categoryMenu[] = '-';
if ($modx->hasPermission('new_'.$elementIdentifer)) {
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

/* loop through categories */
foreach ($categories as $category) {
    if ($category->get('elementCount') <= 0) continue;

    array_unshift($categoryMenu,array(
        'text' => '<b>'.$category->get('category').'</b>',
        'params' => '',
        'handler' => 'function() { return false; }',
        'header' => true,
    ));

    $nodes[] = array(
        'text' => $category->get('category') . ' (' . $category->get('id') . ')',
        'id' => 'n_'.$g[0].'_category_'.($category->get('id') != null ? $category->get('id') : 0),
        'pk' => $category->get('id'),
        'category' => $category->get('id'),
        'leaf' => false,
        'cls' => 'icon-category',
        'href' => '',
        'type' => $elementIdentifer,
        'menu' => array('items' => $categoryMenu),
    );
    array_shift($categoryMenu);
}

/* all elements in category */

$c = $modx->newQuery($elementClassKey);
$c->where(array(
    'category' => $categoryId
));
$c->sortby($elementIdentifer == 'template' ? 'templatename' : 'name','ASC');

$elements = $modx->getCollection($elementClassKey,$c);
foreach ($elements as $element) {
    $name = $elementIdentifer == 'template' ? $element->get('templatename') : $element->get('name');

    $menu = array();
    $menu[] = array(
        'text' => '<b>'.$name.'</b>',
        'params' => '',
        'handler' => 'function() { return false; }',
        'header' => true,
    );
    $menu[] = '-';
    if ($modx->hasPermission('edit_'.$elementIdentifer)) {
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
    if ($modx->hasPermission('edit_chunk') && $elementType == 'Chunk') {
        $menu[] = array(
            'text' => $modx->lexicon('chunk_update_quick'),
            'handler' => 'function(itm,e) {
                this.quickUpdateChunk(itm,e);
            }',
        );
    }
    if ($modx->hasPermission('new_'.$elementIdentifer)) {
        $menu[] = array(
            'text' => $modx->lexicon('duplicate').' '.$elementType,
            'handler' => 'function(itm,e) {
                this.duplicateElement(itm,e,'.$element->get('id').',"'.strtolower($elementType).'");
            }',
        );
    }
    if ($modx->hasPermission('delete_'.$elementIdentifer)) {
        $menu[] = '-';
        $menu[] = array(
            'text' => $modx->lexicon('remove').' '.$elementType,
            'handler' => 'function(itm,e) {
                this.removeElement(itm,e);
            }',
        );
    }

    $nodes[] = array(
        'text' => $name . ' (' . $element->get('id') . ')',
        'id' => 'n_'.$elementIdentifer.'_element_'.$element->get('id').'_'.$element->get('category'),
        'pk' => $element->get('id'),
        'category' => $cat_id,
        'leaf' => 1,
        'cls' => 'icon-'.$elementIdentifer,
        'href' => 'index.php?a='.$ar_actionmap[$elementIdentifer].'&id='.$element->get('id'),
        'type' => $elementIdentifer,
        'menu' => array('items' => $menu),
    );
}

return $nodes;