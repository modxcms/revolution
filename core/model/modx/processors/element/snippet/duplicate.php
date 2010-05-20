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
$old_snippet = $modx->getObject('modSnippet',$scriptProperties['id']);
if (!$old_snippet) return $modx->error->failure($modx->lexicon('snippet_err_nf'));

if (!$old_snippet->checkPolicy('save')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* format new name */
$newname = !empty($scriptProperties['name']) ? $scriptProperties['name'] : $modx->lexicon('duplicate_of').$old_snippet->get('name');

/* check for duplicate name */
$alreadyExists = $modx->getObject('modSnippet',array(
    'name' => $newname,
));
if ($alreadyExists) return $modx->error->failure($modx->lexicon('snippet_err_exists_name'));

/* duplicate snippet */
$snippet = $modx->newObject('modSnippet');
$snippet->fromArray($old_snippet->toArray());
$snippet->set('name',$newname);

if ($snippet->save() === false) {
	return $modx->error->failure($modx->lexicon('snippet_err_duplicate'));
}

/* log manager action */
$modx->logManagerAction('snippet_duplicate','modSnippet',$snippet->get('id'));

return $modx->error->success('',$snippet->get(array_diff(array_keys($snippet->_fields), array('snippet'))));