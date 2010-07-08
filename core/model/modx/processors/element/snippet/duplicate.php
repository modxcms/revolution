<?php
/**
 * Duplicate a snippet.
 *
 * @param integer $id The ID of the snippet to duplicate.
 * @param string $name (optional) The name of the new snippet. Defaults to
 * Untitled Snippet.
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
if (!$modx->hasPermission('new_snippet')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('snippet');

/* get old snippet */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('snippet_err_ns'));
$sourceSnippet = $modx->getObject('modSnippet',$scriptProperties['id']);
if (!$sourceSnippet) return $modx->error->failure($modx->lexicon('snippet_err_nf'));

if (!$sourceSnippet->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* format new name */
$newName = !empty($scriptProperties['name'])
    ? $scriptProperties['name']
    : $modx->lexicon('duplicate_of',array(
        'name' => $sourceSnippet->get('name'),
    ));

/* check for duplicate name */
$alreadyExists = $modx->getObject('modSnippet',array(
    'name' => $newName,
));
if ($alreadyExists) return $modx->error->failure($modx->lexicon('snippet_err_exists_name',array('name' => $newName)));

/* duplicate snippet */
$snippet = $modx->newObject('modSnippet');
$snippet->fromArray($sourceSnippet->toArray());
$snippet->set('name',$newName);

if ($snippet->save() === false) {
    $modx->error->checkValidation($snippet);
    return $modx->error->failure($modx->lexicon('snippet_err_duplicate'));
}

/* log manager action */
$modx->logManagerAction('snippet_duplicate','modSnippet',$snippet->get('id'));

return $modx->error->success('',$snippet->get(array_diff(array_keys($snippet->_fields), array('snippet'))));