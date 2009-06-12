<?php
/**
 * Delete a snippet.
 *
 * @param integer $id The ID of the snippet.
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
$modx->lexicon->load('snippet');

if (!$modx->hasPermission('delete_snippet')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get snippet */
if (empty($_POST['id'])) return $modx->error->failure($modx->lexicon('snippet_err_ns'));
$snippet = $modx->getObject('modSnippet',$_POST['id']);
if ($snippet == null) return $modx->error->failure($modx->lexicon('snippet_err_not_found'));

/* invoke OnBeforeSnipFormDelete event */
$modx->invokeEvent('OnBeforeSnipFormDelete',array('id' => $snippet->get('id')));

/* remove snippet */
if ($snippet->remove() == false) {
	return $modx->error->failure($modx->lexicon('snippet_err_delete'));
}

/* invoke OnSnipFormDelete event */
$modx->invokeEvent('OnSnipFormDelete',array(
    'id' => $snippet->get('id')
));

/* log manager action */
$modx->logManagerAction('snippet_delete','modSnippet',$snippet->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success();