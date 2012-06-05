<?php
/**
 * Gets a list of ACLs.
 *
 * @param string $type The type of ACL object
 * @param string $target (optional) The target of the ACL. Defauls to 0.
 * @param string $principal_class The class_key for the principal. Defaults to
 * modUserGroup.
 * @param string $principal (optional) The principal ID. Defaults to 0.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.access
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access');

if (empty($scriptProperties['type'])) {
    return $modx->error->failure($modx->lexicon('access_type_err_ns'));
}
$accessClass = $scriptProperties['type'];

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

$targetClass = str_replace('Access', '', $accessClass);
$targetId = isset($scriptProperties['target']) ? $scriptProperties['target'] : 0;
$principalClass = isset($scriptProperties['principal_class']) ? $scriptProperties['principal_class'] : 'modUserGroup';
$principalId = isset($scriptProperties['principal']) ? intval($scriptProperties['principal']) : 0;

/* build query */
$c = $modx->newQuery($accessClass);
$c->select($modx->getSelectColumns($accessClass,$accessClass,''));
if ($targetId) {
    $c->where(array('target' => $targetId));
}
$c->where(array('principal_class' => $principalClass));
if ($principalId) {
    $c->where(array('principal' => $principalId));
}
$count = $modx->getCount($accessClass, $c);

if (!empty($sort)) {
    $c->sortby($sort,$dir);
}
if ($sort != 'target') $c->sortby('target', 'ASC');
if ($sort != 'principal_class') $c->sortby('principal_class', 'DESC');
if ($sort != 'principal') $c->sortby('principal', 'ASC');
if ($sort != 'authority') $c->sortby('authority', 'ASC');
if ($sort != 'policy') $c->sortby('policy', 'ASC');
if($isLimit) {
    $c->limit($limit, $start);
}
$objectGraph = '{"Target":{},"Policy":{}}';
$collection = $modx->getCollectionGraph($accessClass, $objectGraph, $c);

$data = array();
foreach ($collection as $key => $object) {
    $principal = $modx->getObject($object->get('principal_class'), $object->get('principal'));
    if (!$principal) $principal = $modx->newObject($object->get('principal_class'), array('name' => '(anonymous)'));
    $policyName = !empty($object->Policy) ? $object->Policy->get('name') : $modx->lexicon('no_policy_option');

    if ($object->Target) {
        $targetName = $accessClass == 'modAccessContext' ? $object->Target->get('key') : $object->Target->get('name');
    } else {
        $targetName = '(anonymous)';
    }

    $objdata= array(
        'id' => $object->get('id'),
        'target' => $object->get('target'),
        'target_name' => $targetName,
        'principal_class' => $object->get('principal_class'),
        'principal' => $object->get('principal'),
        'principal_name' => $principal->get('name'),
        'authority' => $object->get('authority'),
        'policy' => $object->get('policy'),
        'policy_name' => $policyName,
    );
    if (isset($object->_fieldMeta['context_key'])) {
        $objdata['context_key']= $object->get('context_key');
    }
    $cls = '';
    if (($object->get('target') == 'mgr') && $principal->get('name') == 'Administrator' && $policyName == 'Administrator') {} else {
        $cls .= 'pedit premove';
    }

    $objdata['cls'] = $cls;
    $data[] = $objdata;
}
return $this->outputArray($data, $count);
