<?php
/**
 * Gets a policy
 *
 * @param integer $id The ID of the policy
 *
 * @package modx
 * @subpackage processors.security.access.policy
 */
$modx->lexicon->load('policy');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id'])) {
    return $modx->error->failure('Policy id not specified!');
}
$objId = $_REQUEST['id'];

$data = array();
if ($obj = $modx->getObject('modAccessPolicy', $objId)) {
    $dataCol = array('data');
    $data = $obj->get(array(
        'id',
        'name',
        'description',
        'class',
        'parent'
    ));
    $policyData = trim($obj->_fields['data']);
    if ($policySplit = xPDO :: escSplit(',', trim($policyData, '{}'), '"')) {
        $policyData = '{' . "\n" . implode(",\n", $policySplit) . "\n" . '}';
    }
    $data['policy_data'] = $policyData;
}
return $modx->error->success('', $data);