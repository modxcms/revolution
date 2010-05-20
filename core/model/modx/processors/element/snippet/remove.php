<?php
/**
 * Delete a snippet.
 *
 * @param integer $id The ID of the snippet.
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
if (!$modx->hasPermission('delete_snippet')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('snippet');

/* get snippet */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('snippet_err_ns'));
$snippet = $modx->getObject('modSnippet',$scriptProperties['id']);
if (!$snippet) return $modx->error->failure($modx->lexicon('snippet_err_nf'));

if (!$snippet->checkPolicy('remove')) {
    return $modx->error->failure($modx->lexicon('access_denied'));
}

/* invoke OnBeforeSnipFormDelete event */
$modx->invokeEvent('OnBeforeSnipFormDelete',array(
    'id' => $snippet->get('id'),
    'snippet' => &$snippet,
));

/* remove snippet */
if ($snippet->remove() == false) {
    return $modx->error->failure($modx->lexicon('snippet_err_delete'));
}

/* invoke OnSnipFormDelete event */
$modx->invokeEvent('OnSnipFormDelete',array(
    'id' => $snippet->get('id'),
    'snippet' => &$snippet,
));

/* log manager action */
$modx->logManagerAction('snippet_delete','modSnippet',$snippet->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success('',$snippet->get(array('id','name','description','category','locked')));