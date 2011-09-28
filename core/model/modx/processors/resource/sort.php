<?php
/**
 * Sorts the resource tree
 *
 * @param string $data The encoded tree data
 *
 * @package modx
 * @subpackage processors.layout.tree.resource
 */
if (!$modx->hasPermission('save_document')) return $modx->error->failure($modx->lexicon('access_denied'));
$modx->lexicon->load('resource', 'context');

$data = urldecode($scriptProperties['data']);
$data = $modx->fromJSON($data);
$nodes = array();
getNodesFormatted($nodes,$data);

$modx->invokeEvent('OnResourceBeforeSort',array(
    'nodes' => &$nodes,
));

/* readjust cache */
$nodeErrors = array();
$modifiedNodes = array();
$contextsAffected = array();
$dontChangeParents = array();
foreach ($nodes as $ar_node) {
    if (!is_array($ar_node) || empty($ar_node['id'])) continue;
    $node = $modx->getObject('modResource',$ar_node['id']);
    if (empty($node)) continue;

    if (empty($ar_node['context'])) continue;
    if (in_array($ar_node['parent'],$dontChangeParents)) continue;

    $old_parent_id = $node->get('parent');

    if ($old_parent_id != $ar_node['parent']) {
        /* get new parent, if invalid, skip, unless is root */
        if ($ar_node['parent'] != 0) {
            $parent = $modx->getObject('modResource',$ar_node['parent']);
            if ($parent == null) {
                $nodeErrors[] = $modx->lexicon('resource_err_new_parent_nf', array('id' => $ar_node['parent']));
                continue;
            }
            if (!$parent->checkPolicy('add_children')) {
                $nodeErrors[] = $modx->lexicon('resource_add_children_access_denied');
                continue;
            }
        } else {
            $context = $modx->getObject('modContext',$ar_node['context']);
            if (empty($context)) {
                $nodeErrors[] = $modx->lexicon('context_err_nfs', array('key' => $ar_node['context']));
                continue;
            }
            if (!$modx->hasPermission('new_document_in_root')) {
                $nodeErrors[] = $modx->lexicon('resource_add_children_access_denied');
                continue;
            }
        }

        /* save new parent */
        $node->set('parent',$ar_node['parent']);
    }
    $old_context_key = $node->get('context_key');
    $contextsAffected[$old_context_key] = true;
    if ($old_context_key != $ar_node['context'] && !empty($ar_node['context'])) {
        changeChildContext($node, $ar_node['context']); /* recursively move children to new context */
        $node->set('context_key',$ar_node['context']);
        $contextsAffected[$ar_node['context']] = true;
        $dontChangeParents[] = $node->get('id'); /* prevent children from reverting back */
    }
    $node->set('menuindex',$ar_node['order']);
    $modifiedNodes[] = $node;
}
if (!empty($modifiedNodes)) {
    foreach ($modifiedNodes as $modifiedNode) {
        if (!$modifiedNode->checkPolicy('save')) {
            $nodeErrors[] = $modx->lexicon('resource_err_save');
        }
    }
}
if (!empty($nodeErrors)) {
    return $modx->error->failure(implode("\n", array_unique($nodeErrors)));
}
if (!empty($modifiedNodes)) {
    foreach ($modifiedNodes as $modifiedNode) {
        $modifiedNode->save();
    }
}

$modx->invokeEvent('OnResourceSort', array(
    'nodes' => &$nodes,
    'modifiedNodes' => &$modifiedNodes
));

/* empty cache */
$modx->cacheManager->refresh(array(
    'db' => array(),
    'auto_publish' => array('contexts' => $contextsAffected),
    'context_settings' => array('contexts' => $contextsAffected),
    'resource' => array('contexts' => $contextsAffected),
));

return $modx->error->success();

function getNodesFormatted(&$ar_nodes,$cur_level,$parent = 0) {
    $order = 0;
    foreach ($cur_level as $id => $children) {
        $ar = explode('_',$id);
        if ($ar[1] != '0') {
            $par = explode('_',$parent);
            $ar_nodes[] = array(
                'id' => $ar[1],
                'context' => $par[0],
                'parent' => $par[1],
                'order' => $order,
            );
            $order++;
        }
        getNodesFormatted($ar_nodes,$children,$id);
    }
}

function changeChildContext(&$node, $context) {
    foreach ($node->getMany('Children') as $child) {
        changeChildContext($child, $context);
        $child->set('context_key', $context);
    }
}