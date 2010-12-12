<?php
/**
 * Update an ACL.
 *
 * @param string $type The type of ACL object
 * @param string $target (optional) The target of the ACL. Defauls to 0.
 * @param string $principal_class The class_key for the principal. Defaults to
 * modUserGroup.
 * @param string $principal (optional) The principal ID. Defaults to 0.
 * @param integer $authority (optional) The authority of the acl role. Defaults
 * to 9999.
 * @param integer $policy (optional) The ID of the policy. Defaults to 0.
 * @param string $context_key (optional) The context to assign this ACL to.
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

if ($acl = $modx->getObject($accessClass, $accessId)) {
    $acl->fromArray($scriptProperties);
    if (!$acl->save()) {
        return $modx->error->failure($modx->lexicon('access_err_save'));
    } elseif ($modx->getUser()) {
        $modx->user->getAttributes(array(), '', true);
    }
}
return $modx->error->success();
