<?php
/**
 * Sort menu items for a tree
 *
 * @param json $data
 *
 * @package modx
 * @subpackage processors.system.menu
 */
if (!$modx->hasPermission('menus')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('action','menu');

if (empty($scriptProperties['data'])) return $modx->error->failure();
$data = urldecode($scriptProperties['data']);
$data = $modx->fromJSON($data);
$nodes = array();
getNodesFormatted($nodes,$data);

/* readjust cache */
foreach ($nodes as $node) {
    $menu = $modx->getObject('modMenu',$node['text']);
    if (empty($menu)) continue;

    if ($menu->get('parent') != $node['parent']) {
        /* get new parent, if invalid, skip, unless is root */
        if (!empty($node['parent'])) {
            $parentMenu = $modx->getObject('modMenu',$node['parent']);
            if (empty($parentMenu)) continue;
        }

        /* save new parent */
        $menu->set('parent',$node['parent']);
    }
    $menu->set('menuindex',$node['order']);
    $menu->save();
}
return $modx->error->success();

function getNodesFormatted(&$ar_nodes,$cur_level,$parent = '') {
	$order = 0;
	foreach ($cur_level as $id => $children) {
		$id = str_replace('n_','',$id);
		$ar_nodes[] = array(
			'text' => $id,
			'parent' => $parent,
			'order' => $order,
		);
		$order++;
		getNodesFormatted($ar_nodes,$children,$id);
	}
}