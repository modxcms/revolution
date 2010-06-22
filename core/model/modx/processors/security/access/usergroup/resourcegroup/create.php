<?php
/**
 * @package modx
 * @subpackage processors.security.group.resourcegroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access','user');

/* validate for empty fields */
if (!isset($scriptProperties['principal'])) $modx->error->addField('principal',$modx->lexicon('usergroup_err_ns'));
if (empty($scriptProperties['target'])) $modx->error->addField('target',$modx->lexicon('resource_group_err_ns'));
if (empty($scriptProperties['policy'])) $modx->error->addField('policy',$modx->lexicon('access_policy_err_ns'));
if (!isset($scriptProperties['authority'])) $modx->error->addField('authority',$modx->lexicon('authority_err_ns'));

if ($modx->error->hasError()) return $modx->error->failure();

/* validate for invalid data */
$resourceGroup = $modx->getObject('modResourceGroup',$scriptProperties['target']);
if (empty($resourceGroup)) $modx->error->addField('target',$modx->lexicon('resource_group_err_nf'));

$policy = $modx->getObject('modAccessPolicy',$scriptProperties['policy']);
if (empty($policy)) $modx->error->addField('policy',$modx->lexicon('access_policy_err_nf'));

$alreadyExists = $modx->getObject('modAccessResourceGroup',array(
    'principal' => $scriptProperties['principal'],
    'principal_class' => 'modUserGroup',
    'target' => $scriptProperties['target'],
    'policy' => $scriptProperties['policy'],
    'context_key' => $scriptProperties['context_key'],
));
if ($alreadyExists) $modx->error->addField('target',$modx->lexicon('access_rgroup_err_ae'));

if ($modx->error->hasError()) return $modx->error->failure();

/* create object */
$acl = $modx->newObject('modAccessResourceGroup');
$acl->fromArray($scriptProperties);
$acl->set('principal_class','modUserGroup');
if ($acl->save() == false) {
    return $modx->error->failure($modx->lexicon('access_rgroup_err_save'));
}

return $modx->error->success('',$acl);
