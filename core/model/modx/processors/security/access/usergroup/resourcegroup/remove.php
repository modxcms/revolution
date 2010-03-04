<?php
/**
 * Remove a Resource Group ACL for a usergroup
 *
 * @param integer $id The ID of the ACL
 *
 * @package modx
 * @subpackage processors.security.access.resourcegroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access');

/* get usergroup acl */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('access_rgroup_err_ns'));
$acl = $modx->getObject('modAccessResourceGroup',$scriptProperties['id']);
if (empty($acl)) return $modx->error->failure($modx->lexicon('access_rgroup_err_nf').print_r($scriptProperties,true));

/* remove acl */
if ($acl->remove() == false) {
    return $modx->error->failure($modx->lexicon('access_rgroup_err_remove'));
}

return $modx->error->success();