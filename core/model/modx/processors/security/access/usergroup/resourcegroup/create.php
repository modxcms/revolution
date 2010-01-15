<?php
/**
 * @package modx
 * @subpackage processors.security.group.resourcegroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access','user');

/* validate for empty fields */
if (empty($_POST['principal'])) $modx->error->addField('principal',$modx->lexicon('usergroup_err_ns'));
if (empty($_POST['target'])) $modx->error->addField('target',$modx->lexicon('resource_group_err_ns'));
if (empty($_POST['policy'])) $modx->error->addField('policy',$modx->lexicon('access_policy_err_ns'));
if (!isset($_POST['authority'])) $modx->error->addField('authority',$modx->lexicon('authority_err_ns'));

if ($modx->error->hasError()) return $modx->error->failure();

/* validate for invalid data */
$usergroup = $modx->getObject('modUserGroup',$_POST['principal']);
if (empty($usergroup)) $modx->error->addField('principal',$modx->lexicon('user_group_err_nf'));

$resourceGroup = $modx->getObject('modResourceGroup',$_POST['target']);
if (empty($resourceGroup)) $modx->error->addField('target',$modx->lexicon('resource_group_err_nf'));

$policy = $modx->getObject('modAccessPolicy',$_POST['policy']);
if (empty($policy)) $modx->error->addField('policy',$modx->lexicon('access_policy_err_nf'));

$alreadyExists = $modx->getObject('modAccessResourceGroup',array(
    'principal' => $_POST['principal'],
    'principal_class' => 'modUserGroup',
    'target' => $_POST['target'],
    'policy' => $_POST['policy'],
    'context_key' => $_POST['context_key'],
));
if ($alreadyExists) $modx->error->addField('principal',$modx->lexicon('access_rgroup_err_ae'));

if ($modx->error->hasError()) return $modx->error->failure();

/* create object */
$acl = $modx->newObject('modAccessResourceGroup');
$acl->fromArray($_POST);
$acl->set('principal_class','modUserGroup');
if ($acl->save() == false) {
    return $modx->error->failure($modx->lexicon('access_rgroup_err_save'));
}

return $modx->error->success('',$acl);
