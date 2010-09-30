<?php
/**
 * Creates a context
 *
 * @param string $key The key of the context
 *
 * @package modx
 * @subpackage processors.context
 */
if (!$modx->hasPermission('new_context')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('context');

/* prevent duplicate contexts */
$alreadyExists = $modx->getObject('modContext',$scriptProperties['key']);
if ($alreadyExists != null) $modx->error->addField('key',$modx->lexicon('context_err_ae'));

/* if any errors, return */
if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* create context */
$context= $modx->newObject('modContext');
$context->fromArray($scriptProperties, '', true);
if ($context->save() == false) {
    $modx->error->checkValidation($context);
    return $modx->error->failure($modx->lexicon('context_err_create'));
}

/* ensure that Admin usergroup always has access to this context */
$adminGroup = $modx->getObject('modUserGroup',array('name' => 'Administrator'));
$adminAdminPolicy = $modx->getObject('modAccessPolicy',array('name' => 'Administrator'));
$adminResourcePolicy = $modx->getObject('modAccessPolicy',array('name' => 'Resource'));
if ($adminGroup) {
    if ($adminAdminPolicy) {
        $adminAdminAccess = $modx->newObject('modAccessContext');
        $adminAdminAccess->set('principal',$adminGroup->get('id'));
        $adminAdminAccess->set('principal_class','modUserGroup');
        $adminAdminAccess->set('target',$context->get('key'));
        $adminAdminAccess->set('policy',$adminAdminPolicy->get('id'));
        $adminAdminAccess->save();
    }
    if ($adminResourcePolicy) {
        $adminResourceAccess = $modx->newObject('modAccessContext');
        $adminResourceAccess->set('principal',$adminGroup->get('id'));
        $adminResourceAccess->set('principal_class','modUserGroup');
        $adminResourceAccess->set('target',$context->get('key'));
        $adminResourceAccess->set('policy',$adminResourcePolicy->get('id'));
        $adminResourceAccess->save();
    }
}

if ($modx->getUser()) {
    $modx->user->getAttributes(array(), '', true);
}

/* log manager action */
$modx->logManagerAction('context_create','modContext',$context->get('id'));

return $modx->error->success('', $context);