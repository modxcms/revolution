<?php
/**
 * Get pairing nodes for document groups
 *
 * @deprecated
 * @package modx
 * @subpackage processors.security.documentgroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));


$_REQUEST['id'] = !isset($_REQUEST['id']) ? 0 : str_replace('n_dg_','',$_REQUEST['id']);

$g = $modx->getObject('modResourceGroup',$_REQUEST['id']);
$groups = $modx->getCollection('modResourceGroup');

$da = array();

if ($g == null) {
	foreach ($groups as $group) {
		$da[] = array(
			'text' => $group->get('name'),
			'id' => 'n_dg_'.$group->get('id'),
			'leaf' => 0,
			'type' => 'documentgroup',
			'cls' => 'folder',
		);
	}
} else {
	$ugs = $g->getUserGroupsIn();
	foreach ($ugs as $ug) {
		$da[] = array(
			'text' => $ug->get('name'),
			'id' => 'n_ug_'.$ug->get('id'),
			'leaf' => 1,
			'type' => 'usergroup',
			'cls' => '',
		);
	}
}

return $modx->toJSON($da);