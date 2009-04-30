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
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['document_group'])) return $modx->error->failure($modx->lexicon('document_group_err_not_specified'));
if (!isset($_POST['document'])) return $modx->error->failure($modx->lexicon('document_err_not_specified'));

$dgd = $modx->getObject('modResourceGroupResource',array(
	'document_group' => $_POST['document_group'],
	'document' => $_POST['document'],
));
if ($dgd == null) return $modx->error->failure($modx->lexicon('document_group_document_err_not_found'));

if ($dgd->remove() == false) {
    return $modx->error->failure($modx->lexicon('document_group_document_err_remove'));
}

return $modx->error->success();