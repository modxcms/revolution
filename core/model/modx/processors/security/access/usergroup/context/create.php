<?php
/**
 * @package modx
 * @subpackage processors.security.group.context
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access','user','context');

/* validate for empty fields */
if (!isset($scriptProperties['principal'])) $modx->error->addField('principal',$modx->lexicon('usergroup_err_ns'));
if (empty($scriptProperties['target'])) $modx->error->addField('target',$modx->lexicon('context_err_ns'));
if (empty($scriptProperties['policy'])) $modx->error->addField('policy',$modx->lexicon('access_policy_err_ns'));
if (!isset($scriptProperties['authority'])) $modx->error->addField('authority',$modx->lexicon('authority_err_ns'));


if ($modx->error->hasError()) return $modx->error->failure();

/* validate for invalid data */
$context = $modx->getObject('modContext',$scriptProperties['target']);
if (empty($context)) $modx->error->addField('target',$modx->lexicon('context_err_nf'));

$policy = $modx->getObject('modAccessPolicy',$scriptProperties['policy']);
if (empty($policy)) $modx->error->addField('policy',$modx->lexicon('access_policy_err_nf'));

$alreadyExists = $modx->getObject('modAccessContext',array(
    'principal' => $scriptProperties['principal'],
    'principal_class' => 'modUserGroup',
    'target' => $scriptProperties['target'],
    'policy' => $scriptProperties['policy']
));
if ($alreadyExists) $modx->error->addField('target',$modx->lexicon('access_context_err_ae'));

if ($modx->error->hasError()) return $modx->error->failure();

/* ensure that Admin usergroup always has access to this context, if not adding Admin ACL */
$adminGroup = $modx->getObject('modUserGroup',array('name' => 'Administrator'));
if ((integer) $scriptProperties['principal'] !== $adminGroup->get('id')) {
    $adminContextPolicy = $modx->getObject('modAccessPolicy',array('name' => 'Context'));
    if ($adminGroup) {
        if ($adminContextPolicy) {
            $adminContextAccess = $modx->getObject('modAccessContext',array(
                'principal' => $adminGroup->get('id'),
                'principal_class' => 'modUserGroup',
                'target' => $scriptProperties['target'],
            ));
            if (!$adminContextAccess) {
                $adminContextAccess = $modx->newObject('modAccessContext');
                $adminContextAccess->set('principal',$adminGroup->get('id'));
                $adminContextAccess->set('principal_class','modUserGroup');
                $adminContextAccess->set('target',$scriptProperties['target']);
                $adminContextAccess->set('policy',$adminContextPolicy->get('id'));
                $adminContextAccess->save();
            }
        }
    }
}

/* create object */
$acl = $modx->newObject('modAccessContext');
$acl->fromArray($scriptProperties);
$acl->set('principal_class','modUserGroup');
if ($acl->save() == false) {
    return $modx->error->failure($modx->lexicon('access_context_err_save'));
}

if ($modx->getUser()) {
    $modx->user->getAttributes(array(), '', true);
}

return $modx->error->success('',$acl);
