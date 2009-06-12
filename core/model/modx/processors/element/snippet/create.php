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
$modx->lexicon->load('snippet','category');

if (!$modx->hasPermission('new_snippet')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* data escaping */
if ($_POST['name'] == '') $_POST['name'] = $modx->lexicon('snippet_untitled');

/* get rid of invalid chars */
$invchars = array('!','@','#','$','%','^','&','*','(',')','+','=',
    '[',']','{','}','\'','"',':',';','\\','/','<','>','?',' ',',','`','~');
$_POST['name'] = str_replace($invchars,'',$_POST['name']);


$name_exists = $modx->getObject('modSnippet',array('name' => $_POST['name']));
if ($name_exists != null) $modx->error->addField('name',$modx->lexicon('snippet_err_exists_name'));

if ($modx->error->hasError()) return $modx->error->failure();

/* category */
if (is_numeric($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
} else {
    $category = $modx->getObject('modCategory',array('category' => $_POST['category']));
}
if ($category == null) {
    $category = $modx->newObject('modCategory');
    if ($_POST['category'] == '' || $_POST['category'] == 'null') {
        $category->set('id',0);
    } else {
        $category->set('category',$_POST['category']);
        if ($category->save() == false) {
            return $modx->error->failure($modx->lexicon('category_err_save'));
        }
    }
}

/* invoke OnBeforeSnipFormSave event */
$modx->invokeEvent('OnBeforeSnipFormSave',array(
    'mode' => 'new',
    'id' => 0,
));

/* create new snippet */
$snippet = $modx->newObject('modSnippet');
$snippet->fromArray($_POST);
$snippet->set('locked',!empty($_POST['locked']));
$snippet->set('category',$category->get('id'));
$properties = null;
if (isset($_POST['propdata'])) {
    $properties = $_POST['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) $snippet->setProperties($properties);

/* save snippet */
if ($snippet->save() == false) {
    return $modx->error->failure($modx->lexicon('snippet_err_create'));
}

/* invoke OnSnipFormSave event */
$modx->invokeEvent('OnSnipFormSave',array(
    'mode' => 'new',
    'id' => $snippet->get('id'),
));

/* log manager action */
$modx->logManagerAction('snippet_create','modSnippet',$snippet->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success('',$snippet->get(array_diff(array_keys($snippet->_fields), array('snippet'))));