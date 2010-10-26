<?php
/**
 * Gets policies in node format
 *
 * @deprecated
 * @package modx
 * @subpackage processors.security.access.policy
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

if (!isset($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('invalid_data'));

$policyAttr = explode('_', $scriptProperties['id']);
$policyId = count($policyAttr) == 2 ? $policyAttr[1] : '';

$policy = $modx->getObject('modAccessPolicy', $policyId);

$da = array();
if ($policy == null) {
    $da[] = array(
        'text' => $modx->lexicon('policies'),
        'id' => 'n_0',
        'leaf' => false,
        'type' => 'policy',
        'cls' => 'folder',
    );
} else {
    $da[] = array(
        'text' => $policy->get('name'),
        'id' => 'n_'.$policy->get('id'),
        'leaf' => true,
        'type' => 'policy',
        'cls' => 'file',
    );
}
return $modx->toJSON($da);