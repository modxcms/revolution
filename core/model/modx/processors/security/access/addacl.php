<?php
/**
 * Adds an ACL
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

if (!isset($scriptProperties['type'])) {
    return $modx->error->failure($modx->lexicon('access_type_err_ns'));
}
$accessClass = $scriptProperties['type'];
$targetClass = str_replace('Access', '', $accessClass);
$targetId = isset($scriptProperties['target']) ? $scriptProperties['target'] : 0;
$principalClass = isset($scriptProperties['principal_class']) ? $scriptProperties['principal_class'] : 'modUserGroup';
$principalId = isset($scriptProperties['principal']) ? intval($scriptProperties['principal']) : 0;

$authority = isset($scriptProperties['authority']) ? intval($scriptProperties['authority']) : 9999;
$policy = isset($scriptProperties['policy']) ? intval($scriptProperties['policy']) : 0;
$context = isset($scriptProperties['context_key']) ? $scriptProperties['context_key'] : null;



if (!$targetId || !$principalClass) {
    return $modx->error->failure($modx->lexicon('access_err_create_md'));
}
$c = array(
    'target' => $targetId,
    'principal_class' => $principalClass,
    'principal' => $principalId,
    'authority' => $authority,
    'policy' => $policy
);
if ($context !== null) $c['context_key'] = $context;

$acl = $modx->getObject($accessClass, $c);
if ($acl === null) {
    $acl = $modx->newObject($accessClass);
    $acl->fromArray($scriptProperties);
    if ($acl->save() == false) {
        return $modx->error->failure($modx->lexicon('access_err_save'));
    } elseif ($modx->getUser()) {
        $modx->user->getAttributes(array(), '', true);
    }
} else {
    return $modx->error->failure($modx->lexicon('access_err_ae'));
}

return $modx->error->success();
