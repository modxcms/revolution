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
    $c= $modx->newQuery($itemClass, array('key:!=' => 'mgr'));
    if (!empty($defaultRootId)) {
        $c->where(array(
            "(SELECT COUNT(*) FROM {$modx->getTableName('modResource')} WHERE context_key = modContext.{$modx->escape('key')} AND id IN ({$defaultRootId})) > 0",
        ));
    }
} else {
    $resourceColumns = array(
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
        ,'menutitle'
        ,'hidemenu'
        ,'class_key'
        ,'context_key'
    );
    $itemClass= 'modResource';
    $c= $modx->newQuery($itemClass);
    $c->leftJoin('modResource', 'Child', array('modResource.id = Child.parent'));
    $c->select($modx->getSelectColumns('modResource', 'modResource', '', $resourceColumns));
    $c->select(array(
        'COUNT(Child.id) AS childrenCount'
    ));
    $c->where(array(
        'context_key' => $context,
    ));
    if (empty($node) && !empty($defaultRootId)) {
        $c->where(array(
            'id:IN' => explode(',', $defaultRootId),
            'parent:NOT IN' => explode(',', $defaultRootId),
        ));
    } else {
        $c->where(array(
            'parent' => $node,
        ));
    }
    $c->groupby($modx->getSelectColumns('modResource', 'modResource', '', $resourceColumns), '');
    $c->sortby($sortBy,'ASC');
}

/* grab actions */
$actions = $modx->request->getAllActionIDs();
$hasEditPerm = $modx->hasPermission('edit_document');
$collection = $modx->getCollection($itemClass, $c);

$permissionList = array(
    'save_document' => $modx->hasPermission('save_document') ? 'psave' : '',
    'view_document' => $modx->hasPermission('view_document') ? 'pview' : '',
    'edit_document' => $modx->hasPermission('edit_document') ? 'pedit' : '',
    'new_document' => $modx->hasPermission('new_document') ? 'pnew' : '',
    'delete_document' => $modx->hasPermission('delete_document') ? 'pdelete' : '',
    'undelete_document' => $modx->hasPermission('undelete_document') ? 'pundelete' : '',
    'publish_document' => $modx->hasPermission('publish_document') ? 'ppublish' : '',
    'unpublish_document' => $modx->hasPermission('unpublish_document') ? 'punpublish' : '',
    'resource_quick_create' => $modx->hasPermission('resource_quick_create') ? 'pqcreate' : '',
    'resource_quick_update' => $modx->hasPermission('resource_quick_update') ? 'pqupdate' : '',
    'edit_context' => $modx->hasPermission('edit_context') ? 'pedit' : '',
    'new_context' => $modx->hasPermission('new_context') ? 'pnew' : '',
    'delete_context' => $modx->hasPermission('delete_context') ? 'pdelete' : '',
    'new_context_document' => $modx->hasPermission('new_document') ? 'pnewdoc' : '',
);

$nodeField = $modx->getOption('resource_tree_node_name',$scriptProperties,'pagetitle');
$qtipField = $modx->getOption('resource_tree_node_tooltip',$scriptProperties,'');
$items = array();
$item = reset($collection);
while ($item) {
    $canList = $item->checkPolicy('list');
    if ($canList) {
        if ($itemClass == 'modContext') {
            $class = array();
            $class[] = 'icon-context';
            $class[] = !empty($permissionList['edit_context']) ? $permissionList['edit_context'] : '';
            $class[] = !empty($permissionList['new_context']) ? $permissionList['new_context'] : '';
            $class[] = !empty($permissionList['delete_context']) ? $permissionList['delete_context'] : '';
            $class[] = !empty($permissionList['new_context_document']) ? $permissionList['new_context_document'] : '';
            $class[] = !empty($permissionList['resource_quick_create']) ? $permissionList['resource_quick_create'] : '';

            $items[] = array(
                'text' => $item->get('key'),
                'id' => $item->get('key') . '_0',
                'pk' => $item->get('key'),
                'ctx' => $item->get('key'),
                'leaf' => false,
                'cls' => implode(' ',$class),
                'qtip' => $item->get('description') != '' ? strip_tags($item->get('description')) : '',
                'type' => 'modContext',
                'page' => empty($scriptProperties['nohref']) ? '?a='.$actions['context/update'].'&key='.$item->get('key') : '',
            );
        } else {
            $hasChildren = (int)$item->get('childrenCount') > 0 ? true : false;

            $class = array();
            $class[] = 'icon-'.strtolower(str_replace('mod','',$item->get('class_key')));
            $class[] = $item->isfolder ? ' icon-folder' : 'x-tree-node-leaf icon-resource';
            if (!$item->get('published')) $class[] = 'unpublished';
            if ($item->get('deleted')) $class[] = 'deleted';
            if ($item->get('hidemenu')) $class[] = 'hidemenu';

            $class[] = !empty($permissionList['save_document']) ? $permissionList['save_document'] : '';
            $class[] = !empty($permissionList['view_document']) ? $permissionList['view_document'] : '';
            $class[] = !empty($permissionList['edit_document']) ? $permissionList['edit_document'] : '';
            $class[] = !empty($permissionList['new_document']) ? $permissionList['new_document'] : '';
            $class[] = !empty($permissionList['delete_document']) ? $permissionList['delete_document'] : '';
            $class[] = !empty($permissionList['undelete_document']) ? $permissionList['undelete_document'] : '';
            $class[] = !empty($permissionList['publish_document']) ? $permissionList['publish_document'] : '';
            $class[] = !empty($permissionList['unpublish_document']) ? $permissionList['unpublish_document'] : '';
            $class[] = !empty($permissionList['resource_quick_create']) ? $permissionList['resource_quick_create'] : '';
            $class[] = !empty($permissionList['resource_quick_update']) ? $permissionList['resource_quick_update'] : '';
            if ($hasChildren) $class[] = 'haschildren';
            if (!empty($scriptProperties['currentResource']) && $scriptProperties['currentResource'] == $item->id) {
                $class[] = 'active-node';
            }

            $qtip = '';
            if (!empty($qtipField)) {
                $qtip = '<b>'.strip_tags($item->$qtipField).'</b>';
            } else {
                if ($item->longtitle != '') {
                    $qtip = '<b>'.strip_tags($item->longtitle).'</b><br />';
                }
                if ($item->description != '') {
                    $qtip = '<i>'.strip_tags($item->description).'</i>';
                }
            }

            $locked = $item->getLock();
            if ($locked && $locked != $modx->user->get('id')) {
                $class[] = 'icon-locked';
                $lockedBy = $modx->getObject('modUser',$locked);
                if ($lockedBy) {
                    $qtip .= ' - '.$modx->lexicon('locked_by',array('username' => $lockedBy->get('username')));
                }
            }

            $idNote = $modx->hasPermission('tree_show_resource_ids') ? ' <span dir="ltr">('.$item->id.')</span>' : '';
            $itemArray = array(
                'text' => strip_tags($item->$nodeField).$idNote,
                'id' => $item->context_key . '_'.$item->id,
                'pk' => $item->id,
                'cls' => implode(' ',$class),
                'type' => 'modResource',
                'classKey' => $item->class_key,
                'ctx' => $item->context_key,
                'qtip' => $qtip,
                'preview_url' => $modx->makeUrl($item->get('id'), '', '', 'full'),
                'page' => empty($scriptProperties['nohref']) ? '?a='.($hasEditPerm ? $actions['resource/update'] : $actions['resource/data']).'&id='.$item->id : '',
                'allowDrop' => true,
            );
            if (!$hasChildren) {
                $itemArray['hasChildren'] = false;
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
