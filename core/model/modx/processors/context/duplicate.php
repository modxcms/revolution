<?php
/**
 * Duplicates a context.
 *
 * @param string $key The key of the context
 *
 * @package modx
 * @subpackage processors.context
 */
if (!$modx->hasPermission('new_context')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('context');

/* get context */
if (empty($scriptProperties['key'])) return $modx->error->failure($modx->lexicon('context_err_ns'));
$oldContext= $modx->getObject('modContext', $scriptProperties['key']);
if (!$oldContext) return $modx->error->failure($modx->lexicon('context_err_nfs',array('key' => $scriptProperties['key'])));

/* make sure the new key is a valid PHP identifier with no underscore characters */
if (empty($scriptProperties['newkey']) || !preg_match('/^[a-zA-Z\x7f-\xff][a-zA-Z0-9\x7f-\xff]*$/', $scriptProperties['newkey'])) $modx->error->addField('newkey', $modx->lexicon('context_err_ns_key'));

$alreadyExists = $modx->getCount('modContext',array('key' => $scriptProperties['newkey']));
if ($alreadyExists > 0) $modx->error->addField('newkey',$modx->lexicon('context_err_ae'));

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* create new context */
$newContext = $modx->newObject('modContext');
$newContext->set('key',$scriptProperties['newkey']);
if ($newContext->save() == false) {
    return $modx->error->failure($modx->lexicon('context_err_duplicate'));
}

/* duplicate settings */
$settings = $modx->getCollection('modContextSetting',array(
    'context_key' => $oldContext->get('key'),
));
foreach ($settings as $setting) {
    $newSetting = $modx->newObject('modContextSetting');
    $newSetting->fromArray($setting->toArray(),'',true,true);
    $newSetting->set('context_key',$newContext->get('key'));
    $newSetting->save();
}

/* now duplicate resources by level */
duplicateLevel($modx,$oldContext->get('key'),$newContext->get('key'));

function duplicateLevel(modX &$modx,$oldKey,$newKey,$parent = 0,$newParent = 0) {
    $resources = $modx->getCollection('modResource',array(
        'context_key' => $oldKey,
        'parent' => $parent,
    ));
    if (count($resources) <= 0) return array();

    foreach ($resources as $oldResource) {
        $oldResourceArray = $oldResource->toArray();

        $newResource = $modx->newObject('modResource');
        $newResource->fromArray($oldResourceArray);
        $newResource->set('parent',$newParent);
        $newResource->set('context_key',$newKey);
        $newResource->save();

        duplicateLevel($modx,$oldKey,$newKey,$oldResourceArray['id'],$newResource->get('id'));
    }
}

return $modx->error->success('',$newContext);