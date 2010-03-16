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
if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('lexicon');

/* verify file exists */
if (empty($scriptProperties['lexicon'])) return $modx->error->failure($modx->lexicon('lexicon_import_err_ns'));
$_FILE = $scriptProperties['lexicon'];
if (!empty($_FILE['error'])) return $modx->error->failure($modx->lexicon('lexicon_import_err_upload'));

/* get namespace */
if (empty($scriptProperties['namespace'])) return $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$scriptProperties['namespace']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

/* get topic */
if (empty($scriptProperties['topic'])) {
    return $modx->error->failure($modx->lexicon('topic_err_ns'));
}
$topic = $modx->getObject('modLexiconTopic',array(
    'name' => $scriptProperties['topic'],
    'namespace' => $namespace->get('name'),
));
/* if new topic, create */
if ($topic == null) {
    $topic = $modx->newObject('modLexiconTopic');
    $topic->set('name',$scriptProperties['topic']);
    $topic->set('namespace',$namespace->get('name'));
    $topic->save();
}

/* get language */
if (empty($scriptProperties['language'])) return $modx->error->failure($modx->lexicon('language_err_nf'));
$language = $modx->getObject('modLexiconLanguage',$scriptProperties['language']);
/* if new language, create */
if ($language == null) {
    $language = $modx->newObject('modLexiconLanguage');
    $language->set('name',$scriptProperties['language']);
    $language->save();
}

$_lang = array();
if (file_exists($_FILE['tmp_name']) && is_readable($_FILE['tmp_name'])) {
    try {
        @include $_FILE['tmp_name'];
    } catch (Exception $e) {
        return $modx->error->failure($e->getMessage());
    }
} else {
    return $modx->error->failure($modx->lexicon('lexicon_import_err_upload'));
}

if (is_array($_lang)) {
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
}

return $modx->error->success('',array(
    'namespace' => $namespace->get('name'),
    'topic' => $topic->get('id'),
));