<?php
/**
 * Remove an ACL.
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

$acl = $modx->getObject($scriptProperties['type'], $scriptProperties['id']);
if ($acl === null) return $modx->error->failure($modx->lexicon('access_err_nf'));

if ($acl->remove() == false) {
    return $modx->error->failure($modx->lexicon('access_err_remove'));
} elseif ($modx->getUser()) {
    $modx->user->getAttributes(array(), '', true);
}

return $modx->error->success();
