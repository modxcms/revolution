<?php
/**
 * Sort menu items for a tree
 *
 * @param json $data
 *
 * @package modx
 * @subpackage processors.system.menu
 */
$modx->lexicon->load('action','menu');

if (!$modx->hasPermission('menus')) return $modx->error->failure($modx->lexicon('permission_denied'));

$data = urldecode($_POST['data']);
$data = $modx->fromJSON($data);
$nodes = array();
getNodesFormatted($nodes,$data);

/* readjust cache */
foreach ($nodes as $ar_node) {
	$node = $modx->getObject('modMenu',$ar_node['text']);
	if ($node == null) continue;
	$old_parent_id = $node->get('parent');

	if ($old_parent_id != $ar_node['parent']) {
		/* get new parent, if invalid, skip, unless is root */
		if ($ar_node['parent'] != 0) {
			$parent = $modx->getObject('modMenu',$ar_node['parent']);
			if ($parent == null) continue;
		}

		/* save new parent */
		$node->set('parent',$ar_node['parent']);
	}
	$node->set('menuindex',$ar_node['order']);
	$node->save();
}

return $modx->error->success();

function getNodesFormatted(&$ar_nodes,$cur_level,$parent = 0) {
	$order = 0;
	foreach ($cur_level as $id => $children) {
		$id = explode('_',$id);
		$id = $id[1];
		$ar_nodes[] = array(
			'text' => $id,
			'parent' => $parent,
			'order' => $order,
		);
		$order++;
		getNodesFormatted($ar_nodes,$children,$id);
	}
}