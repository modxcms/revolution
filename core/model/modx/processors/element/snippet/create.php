<?php
/**
 * Create a snippet.
 *
 * @param string $name The name of the element
 * @param string $snippet The code of the snippet.
 * @param string $description (optional) A brief description.
 * @param integer $category (optional) The category to assign to. Defaults to no
 * category.
 * @param boolean $locked (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @param json $propdata (optional) A json array of properties
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
if (!$modx->hasPermission('new_snippet')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('snippet','category');

/* data escaping */
if (empty($scriptProperties['name'])) $scriptProperties['name'] = $modx->lexicon('snippet_untitled');

/* make sure name isnt taken */
$nameExists = $modx->getObject('modSnippet',array('name' => $scriptProperties['name']));
if ($nameExists) $modx->error->addField('name',$modx->lexicon('snippet_err_exists_name'));

/* category */
if (!empty($scriptProperties['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $scriptProperties['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
    if (!$category->checkPolicy('add_children')) return $modx->error->failure($modx->lexicon('access_denied'));
}

if ($modx->error->hasError()) return $modx->error->failure();

/* create new snippet */
$snippet = $modx->newObject('modSnippet');
$snippet->fromArray($scriptProperties);
$snippet->set('locked',!empty($scriptProperties['locked']));
$properties = null;
if (isset($scriptProperties['propdata'])) {
    $properties = $scriptProperties['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) $snippet->setProperties($properties);

/* invoke OnBeforeSnipFormSave event */
$modx->invokeEvent('OnBeforeSnipFormSave',array(
    'mode' => modSystemEvent::MODE_NEW,
    'id' => 0,
    'snippet' => &$snippet,
));

if (!$snippet->validate()) {
    $validator = $snippet->getValidator();
    if ($validator->hasMessages()) {
        foreach ($validator->getMessages() as $message) {
            $modx->error->addField($message['field'], $modx->lexicon($message['message']));
        }
    }
    if ($modx->error->hasError()) {
        return $modx->error->failure();
    }
}

/* save snippet */
if ($snippet->save() == false) {
    return $modx->error->failure($modx->lexicon('snippet_err_create'));
}

/* invoke OnSnipFormSave event */
$modx->invokeEvent('OnSnipFormSave',array(
    'mode' => modSystemEvent::MODE_NEW,
    'id' => $snippet->get('id'),
    'snippet' => &$snippet,
));

/* log manager action */
$modx->logManagerAction('snippet_create','modSnippet',$snippet->get('id'));

/* empty cache */
if (!empty($scriptProperties['clearCache'])) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}

return $modx->error->success('',$snippet->get(array('id','name','description','category','locked')));