<?php
/**
 * Get nodes for the resource tree
 *
 * @param string $id (optional) The parent ID from which to grab. Defaults to
 * 0.
 * @param string $sortBy (optional) The column to sort by. Defaults to
 * menuindex.
 *
 * @package modx
 * @subpackage processors.layout.tree.resource
 */
if (!$modx->hasPermission('resource_tree')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource','context');

/* get default properties */
$sortBy = $modx->getOption('sortBy',$scriptProperties,'menuindex');
$stringLiterals = $modx->getOption('stringLiterals',$scriptProperties,false);
$noMenu = $modx->getOption('noMenu',$scriptProperties,false);

$defaultRootId = $modx->getOption('tree_root_id',null,0);
if (empty($scriptProperties['id'])) {
    $context= 'root';
    $node= $defaultRootId;
} else {
    $parts= explode('_', $scriptProperties['id']);
    $context= isset($parts[0]) ? $parts[0] : 'root';
    $node = !empty($parts[1]) ? intval($parts[1]) : 0;
}
if (!empty($scriptProperties['debug'])) echo '<p style="width: 800px; font-family: \'Lucida Console\'; font-size: 11px">';

/* grab resources */
if (empty($context) || $context == 'root') {
    $itemClass= 'modContext';
    $c= $modx->newQuery($itemClass, array("`key` != 'mgr'"));
    if (!empty($defaultRootId)) {
        $c->where(array(
            '(SELECT COUNT(*) FROM '.$modx->getTableName('modResource').' WHERE `context_key` = `modContext`.`key` AND `id` IN ('.$defaultRootId.')) > 0',
        ));
    }
} else {
    $itemClass= 'modResource';
    $c= $modx->newQuery($itemClass);
    $c->select(array(
        'id'
        ,'pagetitle'
        ,'longtitle'
        ,'alias'
        ,'description'
        ,'parent'
        ,'published'
        ,'deleted'
        ,'isfolder'
        ,'menuindex'
        ,'hidemenu'
        ,'class_key'
        ,'context_key'
    ));
    $c->where(array(
        'context_key' => $context,
    ));
    if (empty($node) && !empty($defaultRootId)) {
        $c->where(array(
            'id IN ('.$defaultRootId.')',
            'parent NOT IN ('.$defaultRootId.')',
        ));
    } else {
        $c->where(array(
            'parent' => $node,
        ));
    }
    $c->sortby($sortBy,'ASC');
}

/* grab actions */
$actions = $modx->request->getAllActionIDs();
$hasEditPerm = $modx->hasPermission('edit_document');

$collection = $modx->getCollection($itemClass, $c);

$items = array();
$item = reset($collection);
while ($item) {
    $canList = $item->checkPolicy('list');
    if ($canList) {
        if ($itemClass == 'modContext') {
            $class= 'icon-context';
            $menu = array();
            if ($modx->hasPermission('edit_context')) {
                $menu[] = array(
                    'text' => $modx->lexicon('edit_context'),
                    'handler' => 'function() {
                        this.loadAction("a=' . $actions['context/update']
                            . '&key=' . $item->get('key')
                        . '");
                    }',
                );
            }
            $menu[] = array(
                'cm-context-refresh',
                'text' => $modx->lexicon('context_refresh'),
                'handler' => 'function(itm,e) {
                    this.refreshNode("'.$item->get('key'). '_0",true);
                }',
            );
            $menu[] = '-';
            if ($modx->hasPermission('new_document')) {
                $menu[] = array(
                    'text' => $modx->lexicon('create'),
                    'handler' => 'new Function("return false;")',
                    'menu' => array(
                        'items' => array(
                            array(
                                'text' => $modx->lexicon('document_create_here'),
                                'scope' => 'this',
                                'handler' => 'function() {
                                    Ext.getCmp("modx-resource-tree").loadAction("'
                                        . 'a=' . $actions['resource/create']
                                        . '&context_key=' . $item->get('key')
                                     . '");
                                }',
                            ),
                            array(
                                'text' => $modx->lexicon('weblink_create_here'),
                                'handler' => 'function() {
                                    Ext.getCmp("modx-resource-tree").loadAction("'
                                        . 'a=' . $actions['resource/create']
                                        . '&class_key=' . 'modWebLink'
                                        . '&context_key=' . $item->get('key') . '");
                                }',
                            ),
                            array(
                                'text' => $modx->lexicon('symlink_create_here'),
                                'handler' => 'function() {
                                    Ext.getCmp("modx-resource-tree").loadAction("'
                                        . 'a=' . $actions['resource/create']
                                        . '&class_key=' . 'modSymLink'
                                        . '&context_key=' . $item->get('key') . '");
                                }',
                            ),
                            array(
                                'text' => $modx->lexicon('static_resource_create_here'),
                                'handler' => 'function() {
                                    Ext.getCmp("modx-resource-tree").loadAction("'
                                        . 'a=' . $actions['resource/create']
                                        . '&class_key=' . 'modStaticResource'
                                        . '&context_key=' . $item->get('key') . '");
                                }',
                            ),
                        ),
                    ),
                );
                $menu[] = array(
                    'text' => $modx->lexicon('quick_create'),
                    'handler' => 'new Function("return false;")',
                    'menu' => array(
                        'items' => array(
                            array(
                                'text' => $modx->lexicon('document'),
                                'scope' => 'this',
                                'handler' => 'function(itm,e) {
                                    Ext.getCmp("modx-resource-tree").quickCreate(itm,e,"modResource","'.$item->get('key').'",0);
                                }',
                            ),
                            array(
                                'text' => $modx->lexicon('weblink'),
                                'scope' => 'this',
                                'handler' => 'function(itm,e) {
                                    Ext.getCmp("modx-resource-tree").quickCreate(itm,e,"modWebLink","'.$item->get('key').'","0");
                                }',
                            ),
                            array(
                                'text' => $modx->lexicon('symlink'),
                                'scope' => 'this',
                                'handler' => 'function(itm,e) {
                                    Ext.getCmp("modx-resource-tree").quickCreate(itm,e,"modSymLink","'.$item->get('key').'","0");
                                }',
                            ),
                            array(
                                'text' => $modx->lexicon('static_resource'),
                                'scope' => 'this',
                                'handler' => 'function(itm,e) {
                                    Ext.getCmp("modx-resource-tree").quickCreate(itm,e,"modStaticResource","'.$item->get('key').'","0");
                                }',
                            ),
                        ),
                    ),
                );
            }
            if ($modx->hasPermission('new_context')) {
                $menu[] = array(
                    'text' => $modx->lexicon('context_duplicate'),
                    'handler' => 'function(itm,e) {
                        this.duplicateContext(itm,e);
                    }',
                );
            }
            if ($modx->hasPermission('delete_context')) {

                $menu[] = array(
                    'text' => $modx->lexicon('context_remove'),
                    'handler' => 'function(itm,e) {
                        this.removeContext(itm,e);
                    }',
                );
            }

            $items[] = array(
                'text' => $item->get('key'),
                'id' => $item->get('key') . '_0',
                'pk' => $item->get('key'),
                'leaf' => false,
                'cls' => $class,
                'qtip' => $item->get('description') != '' ? strip_tags($item->get('description')) : '',
                'type' => 'context',
                'page' => empty($scriptProperties['nohref']) ? '?a='.$actions['context/update'].'&key='.$item->get('key') : '',
                'menu' => $noMenu ? array() : array('items' => $menu),
            );
        } else {
            $menu = array();
            $menu[] = array(
                'text' => '<b>'.strip_tags($item->pagetitle).'</b> <i>('.$item->id.')</i>',
                'params' => '',
                'handler' => 'function() { return false; }',
                'header' => true,
            );
            $menu[] = '-';
            if ($modx->hasPermission('view_document')) {
                $menu[] = array(
                    'text' => $modx->lexicon('resource_view'),
                    'handler' => 'function() {
                        this.loadAction("a='.$actions['resource/data'].'");
                    }',
                );
            }
            if ($modx->hasPermission('edit_document')) {
                $menu[] = array(
                    'text' => $modx->lexicon('resource_edit'),
                    'handler' => 'function() {
                        this.loadAction("a='.$actions['resource/update'].'");
                    }',
                );
                $menu[] = array(
                    'text' => $modx->lexicon('quick_update_resource'),
                    'handler' => 'function(itm,e) {
                        Ext.getCmp("modx-resource-tree").quickUpdate(itm,e,"'.$item->get('class_key').'","'.$item->get('key').'","'.$item->get('id').'");
                    }',
                );
            }
            if ($modx->hasPermission('new_document')) {
                $menu[] = array(
                    'text' => $modx->lexicon('resource_duplicate'),
                    'handler' => 'function(itm,e) {
                        this.duplicateResource(itm,e);
                    }',
                );
            }
            $menu[] = array(
                'text' => $modx->lexicon('resource_refresh'),
                'handler' => 'function() {
                    this.refreshNode("'.$item->context_key.'_'.$item->id.'");
                }',
            );
            $menu[] = '-';


            /* add different resource types */
            if ($modx->hasPermission('new_document')) {
                $menu[] = array(
                    'text' => $modx->lexicon('create'),
                    'handler' => 'new Function("return false;")',
                    'menu' => array(
                        'items' => array(
                            array(
                                'text' => $modx->lexicon('document_create_here'),
                                'scope' => 'this',
                                'handler' => 'function() {
                                    Ext.getCmp("modx-resource-tree").loadAction("'
                                        . 'a=' . $actions['resource/create']
                                        . '&parent=' . $item->id
                                        . '&context_key=' . $item->context_key
                                     . '");
                                }',
                            ),
                            array(
                                'text' => $modx->lexicon('weblink_create_here'),
                                'handler' => 'function() {
                                    Ext.getCmp("modx-resource-tree").loadAction("'
                                        . 'a=' . $actions['resource/create']
                                        . '&class_key=' . 'modWebLink'
                                        . '&parent=' . $item->id
                                        . '&context_key=' . $item->context_key . '");
                                }',
                            ),
                            array(
                                'text' => $modx->lexicon('symlink_create_here'),
                                'handler' => 'function() {
                                    Ext.getCmp("modx-resource-tree").loadAction("'
                                        . 'a=' . $actions['resource/create']
                                        . '&class_key=' . 'modSymLink'
                                        . '&parent=' . $item->id
                                        . '&context_key=' . $item->context_key . '");
                                }',
                            ),
                            array(
                                'text' => $modx->lexicon('static_resource_create_here'),
                                'handler' => 'function() {
                                    Ext.getCmp("modx-resource-tree").loadAction("'
                                        . 'a=' . $actions['resource/create']
                                        . '&class_key=' . 'modStaticResource'
                                        . '&parent=' . $item->id
                                        . '&context_key=' . $item->context_key . '");
                                }',
                            ),
                        ),
                    ),
                );

                $menu[] = array(
                    'text' => $modx->lexicon('quick_create'),
                    'handler' => 'new Function("return false;")',
                    'menu' => array(
                        'items' => array(
                            array(
                                'text' => $modx->lexicon('document'),
                                'scope' => 'this',
                                'handler' => 'function(itm,e) {
                                    Ext.getCmp("modx-resource-tree").quickCreate(itm,e,"modDocument","'.$item->context_key.'","'.$item->get('id').'");
                                }',
                            ),
                            array(
                                'text' => $modx->lexicon('weblink'),
                                'scope' => 'this',
                                'handler' => 'function(itm,e) {
                                    Ext.getCmp("modx-resource-tree").quickCreate(itm,e,"modWebLink","'.$item->context_key.'","'.$item->get('id').'");
                                }',
                            ),
                            array(
                                'text' => $modx->lexicon('symlink'),
                                'scope' => 'this',
                                'handler' => 'function(itm,e) {
                                    Ext.getCmp("modx-resource-tree").quickCreate(itm,e,"modSymLink","'.$item->context_key.'","'.$item->get('id').'");
                                }',
                            ),
                            array(
                                'text' => $modx->lexicon('static_resource'),
                                'scope' => 'this',
                                'handler' => 'function(itm,e) {
                                    Ext.getCmp("modx-resource-tree").quickCreate(itm,e,"modStaticResource","'.$item->context_key.'","'.$item->id.'");
                                }',
                            ),
                        ),
                    ),
                );
            }

            $menu[] = '-';

            if ($item->get('published') && $modx->hasPermission('save_document')) {
                $menu[] = array(
                    'text' => $modx->lexicon('resource_unpublish'),
                    'handler' => 'function(itm,e) {
                        this.unpublishDocument(itm,e);
                    }',
                );
            } elseif ($modx->hasPermission('save_document')) {
                $menu[] = array(
                    'text' => $modx->lexicon('resource_publish'),
                    'handler' => 'function(itm,e) {
                        this.publishDocument(itm,e);
                    }',
                );
            }
            if ($item->get('deleted') && $modx->hasPermission('save_document')) {
                $menu[] = array(
                    'text' => $modx->lexicon('resource_undelete'),
                    'handler' => 'function(itm,e) {
                        this.undeleteDocument(itm,e);
                    }',
                );
            } elseif ($modx->hasPermission('save_document')) {
                $menu[] = array(
                    'text' => $modx->lexicon('resource_delete'),
                    'handler' => 'function(itm,e) {
                        this.deleteDocument(itm,e);
                    }',
                );
            }

            $menu[] = '-';
            $menu[] = array(
                'text' => $modx->lexicon('resource_preview'),
                'handler' => 'function(itm,e) {
                    this.preview(itm,e);
                }',
            );

            $class = 'icon-'.strtolower(str_replace('mod','',$item->get('class_key')));
            $class .= $item->isfolder ? ' icon-folder' : ' x-tree-node-leaf';
            $class .= ($item->get('published') ? '' : ' unpublished')
                .($item->get('deleted') ? ' deleted' : '')
                .($item->get('hidemenu') == 1 ? ' hidemenu' : '');

            $qtip = '';
            if ($item->longtitle != '') {
                $qtip = '<b>'.strip_tags($item->longtitle).'</b><br />';
            }
            if ($item->description != '') {
                $qtip = '<i>'.strip_tags($item->description).'</i>';
            }

            $locked = $item->getLock();
            if ($locked && $locked != $modx->user->get('id')) {
                $class = 'icon-locked';
                $lockedBy = $modx->getObject('modUser',$locked);
                if ($lockedBy) {
                    $qtip .= ' - '.$modx->lexicon('locked_by',array('username' => $lockedBy->get('username')));
                }
            }

            $hasChildren = $item->hasChildren() ? false : true;
            $itemArray = array(
                'text' => strip_tags($item->pagetitle).' ('.$item->id.')',
                'id' => $item->context_key . '_'.$item->id,
                'pk' => $item->id,
                'cls' => $class,
                'type' => 'modResource',
                'qtip' => $qtip,
                'preview_url' => $modx->makeUrl($item->get('id')),
                'page' => empty($scriptProperties['nohref']) ? '?a='.($hasEditPerm ? $actions['resource/update'] : $actions['resource/data']).'&id='.$item->id : '',
                'allowDrop' => true,
                'menu' => $noMenu ? array() : array('items' => $menu),
            );
            if ($hasChildren) {
                $itemArray['hasChildren'] = true;
                $itemArray['children'] = array();
                $itemArray['expanded'] = true;
            }
            $items[] = $itemArray;
            unset($qtip,$class,$menu,$itemArray,$hasChildren);
        }
    }
    $item = next($collection);
}
unset($collection, $item, $actions, $hasEditPerm);

if ($stringLiterals) {
    return $modx->toJSON($items);
} else {
    return $this->toJSON($items);
}
