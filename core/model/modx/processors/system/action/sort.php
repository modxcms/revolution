<?php
/**
 * Sorts actions from a tree
 *
 * @package modx
 * @subpackage processors.system.action
 */
if (!$modx->hasPermission('actions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('action','menu');

if (empty($scriptProperties['data'])) return $modx->error->failure();
$data = urldecode($scriptProperties['data']);
$data = $modx->fromJSON($data);
$nodes = array();
getNodesFormatted($nodes,$data);

/* readjust cache */
foreach ($nodes as $ar_node) {
	$node = $modx->getObject('modAction',$ar_node['id']);
	if ($node == null) continue;
	$old_parent_id = $node->get('parent');

	if ($old_parent_id != $ar_node['parent']) {
		/* get new parent, if invalid, skip, unless is root */
		if ($ar_node['parent'] != 0) {
			$parent = $modx->getObject('modAction',$ar_node['parent']);
			if ($parent == null) continue;
		}

		/* save new parent */
		$node->set('parent',$ar_node['parent']);
	}
	$node->save();
}

return $modx->error->success();

function getNodesFormatted(&$ar_nodes,$cur_level,$parent = 0) {
	$order = 0;
	foreach ($cur_level as $id => $children) {
		$id = explode('_',$id);
		$id = $id[1];
		$ar_nodes[] = array(
			'id' => $id,
			'parent' => $parent,
			'order' => $order,
		);
		$order++;
		getNodesFormatted($ar_nodes,$children,$id);
	}
}