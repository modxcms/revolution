<?php
/**
 * Update a pairing of user-resource groups
 *
 * @package modx
 * @subpackage processors.security.documentgroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

$data = urldecode($_POST['data']);
$data = $modx->fromJSON($data);

$ugs = array();
getUGDGFormatted($ugs,$data);

/* remove any not in the posted map */
$ugdgs = $modx->getCollection('modAccessResourceGroup');
foreach ($ugdgs as $uKey => $u) {
    if (!isset($ugs[$uKey])) { $u->remove(); }
}

/* now loop through new map */
foreach ($ugs as $ugKey => $ar) {
	if ($ar['dg_id'] == 0 || $ar['ug_id'] == 0) continue;

	$ug = $modx->getObject('modUserGroup',$ar['ug_id']);
	if ($ug == null) return $modx->error->failure($modx->lexicon('user_group_err_not_found'));

	$dg = $modx->getObject('modResourceGroup',$ar['dg_id']);
	if ($dg == null) return $modx->error->failure($modx->lexicon('document_group_err_not_found'));

	$ugdg = $modx->getObject('modAccessResourceGroup',array(
		'membergroup' => $ug->get('id'),
		'documentgroup' => $dg->get('id'),
	));
	if ($ugdg != NULL) return $modx->error->failure($modx->lexicon('user_group_document_group_err_already_exists'));

	$ugdg = $modx->newObject('modUserGroupResourceGroup');
	$ugdg->set('membergroup',$ug->get('id'));
	$ugdg->set('documentgroup',$dg->get('id'));
	if (!$ugdg->save()) return $modx->error->failure($modx->lexicon('user_group_document_group_err_create'));
}

return $modx->error->success();


function getUGDGFormatted(&$ar_nodes,$cur_level,$parent = 0) {
	$order = 0;
	foreach ($cur_level as $id => $children) {
		$id = substr($id,2); // get rid of CSS id n_ prefix
		if (substr($id,0,2) == 'ug') {
			$ar_nodes[] = array(
				'ug_id' => substr($id,3),
				'dg_id' => substr($parent,3),
				'order' => $order,
			);
			$order++;
		}
		getUGDGFormatted($ar_nodes,$children,$id);
	}
}