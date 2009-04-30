<?php
/**
 * Deletes a category. Resets all elements with that category to 0.
 *
 * TODO: Move this logic to the modCategory class. Possibly also change grabbing
 * each element class to just modElement.
 *
 * @package modx
 * @subpackage processors.element.category
 */
$modx->lexicon->load('category');

$category = $modx->getObject('modCategory',$_REQUEST['id']);
if ($category == null) return $modx->error->failure($modx->lexicon('category_err_not_found'));

/* Hey friends! It's reset time! */
$plugins = $modx->getCollection('modPlugin',array('category' => $category->get('id')));
foreach ($plugins as $plugin) {
	$plugin->set('category',0);
	$plugin->save();
}

$snippets = $modx->getCollection('modSnippet',array('category' => $category->get('id')));
foreach ($snippets as $snippet) {
	$snippet->set('category',0);
	$snippet->save();
}

$chunks = $modx->getCollection('modChunk',array('category' => $category->get('id')));
foreach ($chunks as $chunk) {
	$chunk->set('category',0);
	$chunk->save();
}

$templates = $modx->getCollection('modTemplate',array('category' => $category->get('id')));
foreach ($templates as $template) {
	$template->set('category',0);
	$template->save();
}

$tvs = $modx->getCollection('modTemplateVar',array('category' => $category->get('id')));
foreach ($tvs as $tv) {
	$tv->set('category',0);
	$tv->save();
}

if ($category->remove() == false) {
    return $modx->error->failure($modx->lexicon('category_err_remove'));
}

/* log manager action */
$modx->logManagerAction('category_delete','modCategory',$category->get('id'));

return $modx->error->success();