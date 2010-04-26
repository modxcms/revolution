<?php
/**
 * Grabs all elements for element tree
 *
 * @param string $id (optional) Parent ID of object to grab from. Defaults to 0.
 *
 * @package modx
 * @subpackage processors.layout.tree.element
 */
if (!$modx->hasPermission('element_tree')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('category','element');

$stringLiterals = !empty($scriptProperties['stringLiterals']) ? true : false;

/* process ID prefixes */
$scriptProperties['id'] = !isset($scriptProperties['id']) ? 0 : (substr($scriptProperties['id'],0,2) == 'n_' ? substr($scriptProperties['id'],2) : $scriptProperties['id']);
$grab = $scriptProperties['id'];

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
$g = explode('_',$grab);

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

if ($stringLiterals) {
    return $modx->toJSON($nodes);
} else {
    return $this->toJSON($nodes);
}