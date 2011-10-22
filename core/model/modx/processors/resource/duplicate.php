<?php
/**
 * Duplicates a resource, and optionally, all of its children.
 *
 * @param integer $id The ID of the resource.
 * @param string $name The new name of the resource that will be created.
 * @param boolean $duplicate_children (optional) If true, will duplicate the
 * resource's children as well. Defaults to false.
 * @return array An array of values of the new resource.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * 
 * @package modx
 * @subpackage processors.resource
 */
if (!$modx->hasPermission('new_document')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

/* setup default properties */
$duplicateChildren = !empty($scriptProperties['duplicate_children']);
$newName = !empty($scriptProperties['name']) ? $scriptProperties['name'] : '';
$prefixDuplicate = !empty($scriptProperties['prefixDuplicate']) ? true : false;

/* @var modResource $oldResource */
$oldResource = $modx->getObject('modResource',$scriptProperties['id']);
if (empty($oldResource)) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $scriptProperties['id'])));

if (!$oldResource->checkPolicy('copy')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* @var modResource $parent */
$parent = $oldResource->getOne('Parent');
if ($parent && !$parent->checkPolicy('add_children')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

$newResource = $oldResource->duplicate(array(
    'newName' => $newName,
    'duplicateChildren' => $duplicateChildren,
    'prefixDuplicate' => $prefixDuplicate,
    'publishedMode' => $modx->getOption('published_mode',$scriptProperties,'preserve'),
));
if (!($newResource instanceof modResource)) {
    return $modx->error->failure($newResource);
}

$modx->invokeEvent('OnResourceDuplicate',array(
    'newResource' => &$newResource,
    'oldResource' => &$oldResource,
));

/* log manager action */
$modx->logManagerAction('duplicate_resource','modResource',$newResource->get('id'));

return $modx->error->success('', array ('id' => $newResource->get('id')));