<?php
/**
 * Remove a resource-resourcegroup pairing
 *
 * @param integer $document_group The ID of the resource group
 * @param integer $document The ID of the resource
 *
 * @package modx
 * @subpackage processors.security.documentgroup
 */
$modx->lexicon->load('access','resource');
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['document_group'])) return $modx->error->failure($modx->lexicon('resource_group_err_ns'));
if (!isset($_POST['document'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));

$dgd = $modx->getObject('modResourceGroupResource',array(
	'document_group' => $_POST['document_group'],
	'document' => $_POST['document'],
));
if ($dgd == null) return $modx->error->failure($modx->lexicon('resource_group_resource_err_nf'));

if ($dgd->remove() == false) {
    return $modx->error->failure($modx->lexicon('resource_group_resource_err_remove'));
}

return $modx->error->success();