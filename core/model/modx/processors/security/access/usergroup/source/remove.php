<?php
/**
 * Remove a Media Source ACL for a usergroup
 *
 * @param integer $id The ID of the ACL
 *
 * @package modx
 * @subpackage processors.security.access.source
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access');

/* get usergroup acl */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('access_source_err_ns'));
$acl = $modx->getObject('sources.modAccessMediaSource',$scriptProperties['id']);
if (empty($acl)) return $modx->error->failure($modx->lexicon('access_source_err_nf').print_r($scriptProperties,true));

/* remove acl */
if ($acl->remove() == false) {
    return $modx->error->failure($modx->lexicon('access_source_err_remove'));
}

return $modx->error->success();