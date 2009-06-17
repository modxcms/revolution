<?php
/**
 * Grabs all elements for element tree
 *
 * @param string $id (optional) Parent ID of object to grab from. Defaults to 0.
 *
 * @package modx
 * @subpackage processors.layout.tree.element
 */
$modx->lexicon->load('category','element');

if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* process ID prefixes */
$_REQUEST['id'] = !isset($_REQUEST['id']) ? 0 : (substr($_REQUEST['id'],0,2) == 'n_' ? substr($_REQUEST['id'],2) : $_REQUEST['id']);
$grab = $_REQUEST['id'];

/* setup maps */
$ar_typemap = array(
    'template' => 'modTemplate',
    'tv' => 'modTemplateVar',
    'chunk' => 'modChunk',
    'snippet' => 'modSnippet',
    'plugin' => 'modPlugin',
    'category' => 'modCategory',
);
$actions = $modx->request->getAllActionIDs();
$ar_actionmap = array(
    'template' => $actions['element/template/update'],
    'tv' => $actions['element/tv/update'],
    'chunk' => $actions['element/chunk/update'],
    'snippet' => $actions['element/snippet/update'],
    'plugin' => $actions['element/plugin/update'],
);

/* split the array */
$g = split('_',$grab);

/* quick create Menu */
$quickCreateMenu = array();
$qcm = array('template','tv','chunk','snippet','plugin');
foreach ($qcm as $type) {
    if ($modx->hasPermission('new_'.$type)) {
        $quickCreateMenu[] = array(
            'text' => $modx->lexicon($type),
            'scope' => 'this',
            'handler' => 'function(itm,e) {
                Ext.getCmp("modx_element_tree").quickCreate(itm,e,"'.$type.'");
            }',
        );
    }
}

$quickCreateMenu = array(
    'text' => $modx->lexicon('quick_create'),
    'handler' => 'new Function("return false;")',
    'menu' => array(
        'items' => $quickCreateMenu,
    ),
);

/* load correct mode */
$nodes = array();
switch ($g[0]) {
    case 'type': /* if in the element, but not in a category */
        $nodes = include dirname(__FILE__).'/getnodes.type.php';
        break;
    case 'root': /* if clicking one of the root nodes */
        $nodes = include dirname(__FILE__).'/getnodes.root.php';
        break;
    case 'category': /* if browsing categories */
        $nodes = include dirname(__FILE__).'/getnodes.category.php';
        break;
    default: /* if clicking a node in a category */
        $nodes = include dirname(__FILE__).'/getnodes.incategory.php';
        break;
}

return $this->toJSON($nodes);