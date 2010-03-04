<?php
/**
 * Get pairing nodes for document groups
 *
 * @deprecated
 * @package modx
 * @subpackage processors.security.documentgroup
 */
$modx->lexicon->load('access');
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));


$scriptProperties['id'] = !isset($scriptProperties['id']) ? 0 : str_replace('n_rg_','',$scriptProperties['id']);

$g = $modx->getObject('modResourceGroup',$scriptProperties['id']);
$groups = $modx->getCollection('modResourceGroup');

$da = array();

if ($g == null) {
	foreach ($groups as $group) {
		$da[] = array(
			'text' => $group->get('name'),
			'id' => 'n_rg_'.$group->get('id'),
			'leaf' => 0,
			'type' => 'resourcegroup',
			'cls' => 'folder',
		);
	}
} else {
	$ugs = $g->getUserGroups();
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