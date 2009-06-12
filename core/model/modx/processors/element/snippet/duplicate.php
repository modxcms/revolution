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
$modx->lexicon->load('snippet');

if (!$modx->hasPermission('new_snippet')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get old snippet */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('snippet_err_ns'));
$old_snippet = $modx->getObject('modSnippet',$_POST['id']);
if ($old_snippet == null) {
    return $modx->error->failure($modx->lexicon('snippet_err_not_found'));
}

/* format new name */
$newname = !empty($_POST['name']) ? $_POST['name'] : $modx->lexicon('duplicate_of').$old_snippet->get('name');

/* get rid of invalid chars */
$invchars = array('!','@','#','$','%','^','&','*','(',')','+','=',
    '[',']','{','}','\'','"',':',';','\\','/','<','>','?',' ',',','`','~');
$newname = str_replace($invchars,'',$newname);

/* check for duplicate name */
$ae = $modx->getObject('modSnippet',array(
    'name' => $newname,
));
if ($ae != null) {
    return $modx->error->failure($modx->lexicon('snippet_err_exists_name'));
}

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