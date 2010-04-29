<?php
/**
 * @package modx
 * @subpackage processors.element
 */
$elementType = ucfirst($g[0]);
$nodes = array();
/* templates */
if ($modx->hasPermission('view_template')) {
    $class = 'icon-template';
    $class .= $modx->hasPermission('new_template') ? ' pnew' : '';
    $class .= $modx->hasPermission('new_category') ? ' pnewcat' : '';
    
    $nodes[] = array(
        'text' => $modx->lexicon('templates'),
        'id' => 'n_type_template',
        'leaf' => false,
        'cls' => $class,
        'page' => '',
        'classKey' => 'root',
        'type' => 'template',
        'draggable' => false,
    );
}

/* TVs */
if ($modx->hasPermission('view_tv')) {
    $class = 'icon-tv';
    $class .= $modx->hasPermission('new_tv') ? ' pnew' : '';
    $class .= $modx->hasPermission('new_category') ? ' pnewcat' : '';

    $nodes[] = array(
        'text' => $modx->lexicon('tmplvars'),
        'id' => 'n_type_tv',
        'leaf' => false,
        'cls' => $class,
        'page' => '',
        'classKey' => 'root',
        'type' => 'tv',
        'draggable' => false,
    );
}

/* chunks */
if ($modx->hasPermission('view_chunk')) {
    $class = 'icon-chunk';
    $class .= $modx->hasPermission('new_chunk') ? ' pnew' : '';
    $class .= $modx->hasPermission('new_category') ? ' pnewcat' : '';

    $nodes[] = array(
        'text' => $modx->lexicon('chunks'),
        'id' => 'n_type_chunk',
        'leaf' => false,
        'cls' => $class,
        'page' => '',
        'classKey' => 'root',
        'type' => 'chunk',
        'draggable' => false,
    );
}

/* snippets */
if ($modx->hasPermission('view_snippet')) {
    $class = 'icon-snippet';
    $class .= $modx->hasPermission('new_snippet') ? ' pnew' : '';
    $class .= $modx->hasPermission('new_category') ? ' pnewcat' : '';

    $nodes[] = array(
        'text' => $modx->lexicon('snippets'),
        'id' => 'n_type_snippet',
        'leaf' => false,
        'cls' => $class,
        'page' => '',
        'classKey' => 'root',
        'type' => 'snippet',
        'draggable' => false,
    );
}

/* plugins */
if ($modx->hasPermission('view_plugin')) {
    $class = 'icon-plugin';
    $class .= $modx->hasPermission('new_snippet') ? ' pnew' : '';
    $class .= $modx->hasPermission('new_category') ? ' pnewcat' : '';

    $nodes[] = array(
        'text' => $modx->lexicon('plugins'),
        'id' => 'n_type_plugin',
        'leaf' => false,
        'cls' => $class,
        'page' => '',
        'classKey' => 'root',
        'type' => 'plugin',
        'draggable' => false,
    );
}

/* categories */
if ($modx->hasPermission('view_category')) {
    $class = 'icon-category';
    $class .= $modx->hasPermission('new_category') ? ' pnewcat' : '';

    $nodes[] = array(
        'text' => $modx->lexicon('categories'),
        'id' => 'n_category',
        'leaf' => 0,
        'cls' => $class,
        'page' => '',
        'classKey' => 'root',
        'type' => 'category',
        'draggable' => false,
    );
}

return $nodes;