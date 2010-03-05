<?php
/**
 * @package modx
 * @subpackage processors.element
 */
$elementType = ucfirst($g[0]);
$nodes = array();
/* templates */
if ($modx->hasPermission('view_template')) {
    $templateMenu = array();
    if ($modx->hasPermission('new_template')) {
        $templateMenu[] = array(
            'text' => $modx->lexicon('new').' '.$modx->lexicon('template'),
            'handler' => 'function(itm,e) { this._createElement(itm,e); }',
        );
        $templateMenu[] = array(
            'text' => $modx->lexicon('quick_create_template'),
            'handler' => 'function(itm,e) {
                this.quickCreate(itm,e,"template");
            }',
        );
    }
    if (!empty($templateMenu)) $templateMenu[] = '-';
    if ($modx->hasPermission('new_category')) {
        $templateMenu[] = array(
            'text' => $modx->lexicon('new_category'),
            'handler' => 'function(itm,e) { this.createCategory(itm,e); }',
        );
    }

    $nodes[] = array(
        'text' => $modx->lexicon('templates'),
        'id' => 'n_type_template',
        'leaf' => false,
        'cls' => 'icon-template',
        'page' => '',
        'data' => array(),
        'type' => 'template',
        'draggable' => false,
        'menu' => array('items' => $templateMenu),
    );
    unset($templateMenu);
}

/* TVs */
if ($modx->hasPermission('view_tv')) {
    $tvMenu = array();
    if ($modx->hasPermission('new_tv')) {
        $tvMenu[] = array(
            'text' => $modx->lexicon('new').' '.$modx->lexicon('tmplvar'),
            'handler' => 'function(itm,e) {
                this._createElement(itm,e);
            }',
        );
        $tvMenu[] = array(
            'text' => $modx->lexicon('quick_create_tv'),
            'handler' => 'function(itm,e) {
                this.quickCreate(itm,e,"tv");
            }',
        );
    }
    if (!empty($tvMenu)) $tvMenu[] = '-';
    if ($modx->hasPermission('new_category')) {
        $tvMenu[] = array(
            'text' => $modx->lexicon('new_category'),
            'handler' => 'function(itm,e) {
                this.createCategory(itm,e);
            }',
        );
    }

    $nodes[] = array(
        'text' => $modx->lexicon('tmplvars'),
        'id' => 'n_type_tv',
        'leaf' => false,
        'cls' => 'icon-tv',
        'page' => '',
        'data' => array(),
        'type' => 'tv',
        'draggable' => false,
        'menu' => array('items' => $tvMenu),
    );
    unset($tvMenu);
}

/* chunks */
if ($modx->hasPermission('view_chunk')) {
    $chunkMenu = array();
    if ($modx->hasPermission('new_chunk')) {
        $chunkMenu[] = array(
            'text' => $modx->lexicon('new').' '.$modx->lexicon('chunk'),
            'handler' => 'function(itm,e) {
                this._createElement(itm,e);
            }',
        );
        $chunkMenu[] = array(
            'text' => $modx->lexicon('quick_create_chunk'),
            'handler' => 'function(itm,e) {
                this.quickCreate(itm,e,"chunk");
            }',
        );
    }
    if (!empty($chunkMenu)) $chunkMenu[] = '-';
    if ($modx->hasPermission('new_category')) {
        $chunkMenu[] = array(
            'text' => $modx->lexicon('new_category'),
            'handler' => 'function(itm,e) {
                this.createCategory(itm,e);
            }',
        );
    }

    $nodes[] = array(
        'text' => $modx->lexicon('chunks'),
        'id' => 'n_type_chunk',
        'leaf' => false,
        'cls' => 'icon-chunk',
        'page' => '',
        'data' => array(),
        'type' => 'chunk',
        'draggable' => false,
        'menu' => array('items' => $chunkMenu),
    );
    unset($chunkMenu);
}

/* snippets */
if ($modx->hasPermission('view_snippet')) {
    $snippetMenu = array();
    if ($modx->hasPermission('new_snippet')) {
        $snippetMenu[] = array(
            'text' => $modx->lexicon('new').' '.$modx->lexicon('snippet'),
            'handler' => 'function(itm,e) {
                this._createElement(itm,e);
            }',
        );
        $snippetMenu[] = array(
            'text' => $modx->lexicon('quick_create_snippet'),
            'handler' => 'function(itm,e) {
                this.quickCreate(itm,e,"snippet");
            }',
        );
    }
    if (!empty($snippetMenu)) $snippetMenu[] = '-';
    if ($modx->hasPermission('new_category')) {
        $snippetMenu[] = array(
            'text' => $modx->lexicon('new_category'),
            'handler' => 'function(itm,e) {
                this.createCategory(itm,e);
            }',
        );
    }

    $nodes[] = array(
        'text' => $modx->lexicon('snippets'),
        'id' => 'n_type_snippet',
        'leaf' => false,
        'cls' => 'icon-snippet',
        'page' => '',
        'data' => array(),
        'type' => 'snippet',
        'draggable' => false,
        'menu' => array('items' => $snippetMenu),
    );
    unset($snippetMenu);
}

/* plugins */
if ($modx->hasPermission('view_plugin')) {
    $pluginMenu = array();
    if ($modx->hasPermission('new_plugin')) {
        $pluginMenu[] = array(
            'text' => $modx->lexicon('new').' '.$modx->lexicon('plugin'),
            'handler' => 'function(itm,e) {
                this._createElement(itm,e);
            }',
        );
        $pluginMenu[] = array(
            'text' => $modx->lexicon('quick_create_plugin'),
            'handler' => 'function(itm,e) {
                this.quickCreate(itm,e,"plugin");
            }',
        );
    }
    if (!empty($pluginMenu)) $pluginMenu[] = '-';
    if ($modx->hasPermission('new_category')) {
        $pluginMenu[] = array(
            'text' => $modx->lexicon('new_category'),
            'handler' => 'function(itm,e) {
                this.createCategory(itm,e);
            }',
        );
    }
    $nodes[] = array(
        'text' => $modx->lexicon('plugins'),
        'id' => 'n_type_plugin',
        'leaf' => false,
        'cls' => 'icon-plugin',
        'page' => '',
        'type' => 'plugin',
        'draggable' => false,
        'menu' => array('items' => $pluginMenu),
    );
    unset($pluginMenu);
}

/* categories */
if ($modx->hasPermission('view_category')) {
    $categoryMenu = array();
    if ($modx->hasPermission('new_category')) {
        $categoryMenu[] = array(
            'text' => $modx->lexicon('new_category'),
            'handler' => 'function(itm,e) {
                this.createCategory(itm,e);
            }',
        );
    }

    $nodes[] = array(
        'text' => $modx->lexicon('categories'),
        'id' => 'n_category',
        'leaf' => 0,
        'cls' => 'icon-category',
        'page' => '',
        'data' => array(),
        'type' => 'root-category',
        'draggable' => false,
        'menu' => array('items' => $categoryMenu),
    );
    unset($categoryMenu);
}

return $nodes;