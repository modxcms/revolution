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
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('snippet_err_ns'));
$old_snippet = $modx->getObject('modSnippet',$_POST['id']);
if (!$old_snippet) return $modx->error->failure($modx->lexicon('snippet_err_nf'));

/* format new name */
$newname = !empty($_POST['name']) ? $_POST['name'] : $modx->lexicon('duplicate_of').$old_snippet->get('name');

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