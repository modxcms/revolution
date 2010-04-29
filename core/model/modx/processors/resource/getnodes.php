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
            $class = 'icon-context';
            $class .= $modx->hasPermission('edit_context') ? ' pedit' : '';
            $class .= $modx->hasPermission('new_context') ? ' pnew' : '';
            $class .= $modx->hasPermission('delete_document') ? ' pdelete' : '';
            $class .= $modx->hasPermission('new_document') ? ' pnewdoc' : '';

            $items[] = array(
                'text' => $item->get('key'),
                'id' => $item->get('key') . '_0',
                'pk' => $item->get('key'),
                'ctx' => $item->get('key'),
                'leaf' => false,
                'cls' => $class,
                'qtip' => $item->get('description') != '' ? strip_tags($item->get('description')) : '',
                'type' => 'modContext',
                'page' => empty($scriptProperties['nohref']) ? '?a='.$actions['context/update'].'&key='.$item->get('key') : '',
            );
        } else {            
            $class = 'icon-'.strtolower(str_replace('mod','',$item->get('class_key')));
            $class .= $item->isfolder ? ' icon-folder' : ' x-tree-node-leaf';
            $class .= ($item->get('published') ? '' : ' unpublished')
                .($item->get('deleted') ? ' deleted' : '')
                .($item->get('hidemenu') == 1 ? ' hidemenu' : '');

            $class .= $modx->hasPermission('save_document') ? ' psave' : '';
            $class .= $modx->hasPermission('view_document') ? ' pview' : '';
            $class .= $modx->hasPermission('edit_document') ? ' pedit' : '';
            $class .= $modx->hasPermission('new_document') ? ' pnew' : '';

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
                'classKey' => $item->class_key,
                'key' => $item->get('key'),
                'ctx' => $item->context_key,
                'qtip' => $qtip,
                'preview_url' => $modx->makeUrl($item->get('id')),
                'page' => empty($scriptProperties['nohref']) ? '?a='.($hasEditPerm ? $actions['resource/update'] : $actions['resource/data']).'&id='.$item->id : '',
                'allowDrop' => true,
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
