<?php
/**
 * Imports lexicon entries from a file
 *
 * @param string $language The IANA code for the language.
 * @param integer $topic The topic associated with this entry.
 * @param string $namespace The namespace associated with this entry.
 *
 * @package modx
 * @subpackage processors.workspace.lexicon.focus
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* verify file exists */
if (!isset($_FILES['lexicon'])) return $modx->error->failure($modx->lexicon('lexicon_import_err_ns'));
$_FILE = $_FILES['lexicon'];
if ($_FILE['error'] != 0) return $modx->error->failure($modx->lexicon('lexicon_import_err_upload'));


/* get namespace */
if (!isset($_POST['namespace'])) return $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$_POST['namespace']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

/* get topic */
if (!isset($_POST['topic']) || $_POST['topic'] == '') {
    return $modx->error->failure($modx->lexicon('topic_err_ns'));
}
$topic = $modx->getObject('modLexiconTopic',array(
    'name' => $_POST['topic'],
    'namespace' => $namespace->get('name'),
));
/* if new topic, create */
if ($topic == null) {
    $topic = $modx->newObject('modLexiconTopic');
    $topic->set('name',$_POST['topic']);
    $topic->set('namespace',$namespace->get('name'));
    $topic->save();
}

/* get language */
if (!isset($_POST['language'])) return $modx->error->failure($modx->lexicon('language_err_nf'));
$language = $modx->getObject('modLexiconLanguage',$_POST['language']);
/* if new language, create */
if ($language == null) {
    $language = $modx->newObject('modLexiconLanguage');
    $language->set('name',$_POST['language']);
    $language->save();
}

$_lang = array();
@include $_FILE['tmp_name'];

foreach ($_lang as $key => $str) {
	$entry = $modx->getObject('modLexiconEntry',array(
        'name' => $key,
        'topic' => $topic->get('id'),
        'namespace' => $namespace->get('name'),
        'language' => $language->get('name'),
    ));
    if ($entry == null) {
    	$entry = $modx->newObject('modLexiconEntry');
        $entry->set('name',$key);
        $entry->set('topic',$topic->get('id'));
        $entry->set('namespace',$namespace->get('name'));
        $entry->set('language',$language->get('name'));
    }
    $entry->set('value',$str);

    $entry->save();
}

return $modx->error->success();