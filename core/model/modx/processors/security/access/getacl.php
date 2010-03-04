<?php
/**
 * Gets an ACL.
 *
 * @param string $type The class_key for the ACL.
 * @param string $id The ID of the ACL.
 *
 * @package modx
 * @subpackage processors.security.access
 */
$modx->lexicon->load('access');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($scriptProperties['type']) || !isset($scriptProperties['id'])) {
    return $modx->error->failure($modx->lexicon('access_type_err_ns'));
}
$accessClass = $scriptProperties['type'];
$accessId = $scriptProperties['id'];

$aclArray = array();
if ($acl = $modx->getObject($accessClass, $accessId)) {
    $aclArray = $acl->toArray();
}

return $modx->error->success('', $aclArray);