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
if (empty($_POST['key'])) return $modx->error->failure($modx->lexicon('context_err_ns'));
$oldContext= $modx->getObject('modContext', $_POST['key']);
if (!$oldContext) return $modx->error->failure($modx->lexicon('context_err_nfs',array('key' => $_POST['key'])));

/* make sure the new key is a valid PHP identifier with no underscore characters */
if (empty($_POST['newkey']) || !preg_match('/^[a-zA-Z\x7f-\xff][a-zA-Z0-9\x7f-\xff]*$/', $_POST['newkey'])) $modx->error->addField('newkey', $modx->lexicon('context_err_ns_key'));

$alreadyExists = $modx->getCount('modContext',array('key' => $_POST['newkey']));
if ($alreadyExists > 0) $modx->error->addField('newkey',$modx->lexicon('context_err_ae'));

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

/* create new context */
$newContext = $modx->newObject('modContext');
$newContext->set('key',$_POST['newkey']);
if ($newContext->save() == false) {
    return $modx->error->failure($modx->lexicon('context_err_duplicate'));
}

/* now duplicate resources by level */
duplicateLevel($modx,$oldContext->get('key'),$newContext->get('key'));

function duplicateLevel(modX &$modx,$oldKey,$newKey,$parent = 0) {
    $resources = $modx->getCollection('modResource',array(
        'context_key' => $oldKey,
        'parent' => $parent,
    ));
    if (count($resources) <= 0) return array();

    foreach ($resources as $oldResource) {
        $oldResourceArray = $oldResource->toArray();

        $newResource = $modx->newObject('modResource');
        $newResource->fromArray($oldResourceArray);
        $newResource->set('parent',$parent);
        $newResource->set('context_key',$newKey);
        $newResource->save();

        duplicateLevel($modx,$oldKey,$newKey,$oldResourceArray['id']);
    }
}

return $modx->error->success('',$newContext);