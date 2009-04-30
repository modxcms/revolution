<?php
/**
 * Grabs resources in the form of tree nodes for the Resource Tree.
 *
 * @param integer $id (optional) The parent to grab nodes from. Defaults to 0.
 * @param string $sortBy (optional) The column to sort by. Defaults to
 * menuindex.
 *
 * @package modx
 * @subpackage processors.resource
 */
$modx->lexicon->load('resource','context');

if (!isset($_REQUEST['sortBy'])) $_REQUEST['sortBy'] = 'menuindex';

if (!isset($_REQUEST['id'])) {
    $context= 'root';
	$node= 0;
} else {
    $parts= explode('_', $_REQUEST['id']);
    $context= isset($parts[0]) ? $parts[0] : 'root';
    $node = isset($parts[1]) ? intval($parts[1]) : 0;
}

$docgrp = '';
$orderby = 'context, '.$_REQUEST['sortBy'].' ASC, isfolder, pagetitle';

/* grab resources */
if (empty($context) || $context == 'root') {
    $itemClass= 'modContext';
    $c= '`key` != \'mgr\'';
} else {
    $itemClass= 'modResource';
    $c= $modx->newQuery('modResource');
    $c->where(array(
        'parent' => $node,
        'context_key' => $context,
    ));
    $c->sortby($_REQUEST['sortBy'],'ASC');
}

/* grab actions */
$actions = $modx->request->getAllActionIDs();

$collection = $modx->getCollection($itemClass, $c);
$items = array();
foreach ($collection as $item) {
    $canList = $item->checkPolicy('list');
    if ($canList) {
        if ($itemClass == 'modContext') {

            $class= 'folder';
            $menu = array(
                array(
                    'id' => 'view_context',
                    'text' => $modx->lexicon('view_context'),
                    'params' => array( 'a' => $actions['context/view'], 'key' => $item->get('key') ),
                ),
                array(
                    'id' => 'edit_context',
                    'text' => $modx->lexicon('edit_context'),
                    'params' => array( 'a' => $actions['context'], 'key' => $item->get('key') ),
                ),
                array(
                    'text' => $modx->lexicon('context_refresh'),
                    'handler' => 'this.refreshNode.createDelegate(this,["'.$item->get('key'). '_0",true])',
                ),
                '-',
                array(
                    'id' => 'create_resource',
                    'text' => $modx->lexicon('resource_create_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'context_key' => $item->get('key'),
                    ),
                ),
                array(
                    'id' => 'create_weblink',
                    'text' => $modx->lexicon('weblink_create_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modWebLink',
                        'context_key' => $item->get('key'),
                    ),
                ),
                array(
                    'id' => 'create_symlink',
                    'text' => $modx->lexicon('create_symlink_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modSymLink',
                        'context_key' => $item->get('key'),
                    ),
                ),
                array(
                    'id' => 'create_static_resource',
                    'text' => $modx->lexicon('static_resource_create_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modStaticResource',
                        'context_key' => $item->get('key'),
                    ),
                ),
            );

            $items[] = array(
                'text' => $item->key,
                'id' => $item->key . '_0',
                'leaf' => 0,
                'cls' => $class,
                'qtip' => $item->get('description'),
                'type' => 'context',
                'href' => 'index.php?a='.$actions['context/view'].'&key='.$item->get('key'),
                'menu' => $menu,
            );
        } else {

            $class = ($item->get('isfolder') ? 'folder' : 'file').($item->get('published') ? '' : ' unpublished').($item->get('deleted') ? ' deleted' : '');

            $menu = array(
                array(
                    'id' => 'doc_header',
                    'text' => '<b>'.$item->get('pagetitle').'</b> <i>('.$item->get('id').')</i>',
                    'params' => '',
                    'handler' => 'new Function("return false");',
                    'header' => true,
                ),'-',
                array(
                    'id' => 'view_document',
                    'text' => $modx->lexicon('resource_view'),
                    'params' => array( 'a' => $actions['resource/data'], ),
                ),
                array(
                    'id' => 'edit_document',
                    'text' => $modx->lexicon('resource_edit'),
                    'params' => array( 'a' => $actions['resource/update'], ),
                ),
                array(
                    'id' => 'duplicate_document',
                    'text' => $modx->lexicon('resource_duplicate'),
                    'handler' => 'this.duplicateResource',
                ),
                array(
                    'text' => $modx->lexicon('resource_refresh'),
                    'handler' => 'this.refreshNode.createDelegate(this,["'.$item->get('context_key') . '_'.$item->get('id').'",false])',
                ),
                '-',
                array(
                    'id' => 'create_document',
                    'text' => $modx->lexicon('resource_create_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'parent' => $item->get('id'),
                        'context_key' => $item->get('context_key'),
                    ),
                ),
                array(
                    'id' => 'create_weblink',
                    'text' => $modx->lexicon('weblink_create_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modWebLink',
                        'parent' => $item->get('id'),
                        'context_key' => $item->get('context_key'),
                    ),
                ),
                array(
                    'id' => 'create_static_resource',
                    'text' => $modx->lexicon('static_resource_create_here'),
                    'params' => array(
                        'a' => $actions['resource/create'],
                        'class_key' => 'modStaticResource',
                        'parent' => $item->get('id'),
                        'context_key' => $item->get('context_key'),
                    ),
                ),'-',
            );

            if ($item->published) {
                $menu[] = array(
                    'id' => 'unpublish_document',
                    'text' => $modx->lexicon('resource_unpublish'),
                    'handler' => 'this.unpublishDocument',
                );
            } else {
                $menu[] = array(
                    'id' => 'publish_document',
                    'text' => $modx->lexicon('resource_publish'),
                    'handler' => 'this.publishDocument',
                );
            }
            if ($item->deleted) {
                $menu[] = array(
                    'id' => 'undelete_document',
                    'text' => $modx->lexicon('resource_undelete'),
                    'handler' => 'this.undeleteDocument',
                );
            } else {
                $menu[] = array(
                    'id' => 'delete_document',
                    'text' => $modx->lexicon('resource_delete'),
                    'handler' => 'this.deleteDocument',
                );
            }

            $menu[] = '-';
            $menu[] = array(
                'id' => 'preview_document',
                'text' => $modx->lexicon('resource_preview'),
                'handler' => 'this.preview',
            );

            $qtip = ($item->get('longtitle') != '' ? '<b>'.$item->get('longtitle').'</b><br />' : '').'<i>'.$item->get('description').'</i>';

            $items[] = array(
                'text' => $item->get('pagetitle').' ('.$item->get('id').')',
                'id' => $item->get('context_key') . '_'.$item->get('id'),
                'leaf' => $item->get('isfolder') ? false : true,
                'cls' => $class,
                'type' => 'modResource',
                'qtip' => $qtip,
                'menu' => $menu,
            );
        }
    }
}

return $modx->toJSON($items);