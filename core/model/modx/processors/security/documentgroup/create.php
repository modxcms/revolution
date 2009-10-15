<?php
/**
 * Create a resource group
 *
 * @param string $name The name of the new resource group
 *
 * @package modx
 * @subpackage processors.security.documentgroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('access');

if (empty($_POST['name'])) $_POST['name'] = $modx->lexicon('resource_group_untitled');

/* make sure name is unique */
$alreadyExists = $modx->getObject('modResourceGroup',array('name' => $_POST['name']));
if ($alreadyExists) return $modx->error->failure($modx->lexicon('resource_group_err_ae'));

/* create resource group */
$resourceGroup = $modx->newObject('modResourceGroup');
$resourceGroup->fromArray($_POST);

if ($resourceGroup->save() == false) {
    return $modx->error->failure($modx->lexicon('resource_group_err_create'));
}

$modx->invokeEvent('OnCreateDocGroup',array(
    'group' => &$resourceGroup,
));

/* log manager action */
$modx->logManagerAction('new_resource_group','modResourceGroup',$resourceGroup->get('id'));

return $modx->error->success('',$resourceGroup);