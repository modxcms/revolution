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
    $c->sortby('category','ASC');
} else {
    /* if trying to grab all root categories */
    $c = $modx->newQuery('modCategory');
    $c->where(array(
        'parent' => 0,
    ));
    $c->sortby('category','ASC');
}

$c->select('
    `modCategory`.*,
    COUNT(`Children`.`id`) AS `childrenCount`
');
$c->leftJoin('modCategory','Children');
$c->groupby('modCategory.id');

/* prepare menu */
$menu = array();
if ($modx->hasPermission('new_category')) {
    $menu[] = array(
        'text' => $modx->lexicon('category_create'),
        'handler' => 'function(itm,e) {
            this.createCategory(itm,e);
        }',
    );
}
if ($modx->hasPermission('edit_category')) {
    $menu[] = array(
        'text' => $modx->lexicon('category_rename'),
        'handler' => 'function(itm,e) {
            this.renameCategory(itm,e);
        }',
    );
}
$menu[] = '-';
$menu[] = $quickCreateMenu;
if ($modx->hasPermission('delete_category')) {
    $menu[] = '-';
    $menu[] = array(
        'text' => $modx->lexicon('category_remove'),
        'handler' => 'function(itm,e) {
            this.removeCategory(itm,e);
        }',
    );
}

/* get and loop through categories */
$categories = $modx->getCollection('modCategory',$c);
foreach ($categories as $category) {
    $nodes[] = array(
        'text' => strip_tags($category->get('category')) . ' (' . $category->get('id') . ')',
        'id' => 'n_category_'.$category->get('id'),
        'pk' => $category->get('id'),
        'data' => $category->toArray(),
        'category' => $category->get('id'),
        'leaf' => $category->get('childrenCount') > 0 ? false : true,
        'cls' => 'icon-category folder',
        'page' => '',
        'type' => 'category',
        'menu' => array(
            'items' => $menu,
        ),
    );
}

return $nodes;