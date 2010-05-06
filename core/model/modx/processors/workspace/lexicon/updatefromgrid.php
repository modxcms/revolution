<?php
/**
 * Updates a lexicon entry from a grid
 *
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('lexicon');

$_DATA = $modx->fromJSON($scriptProperties['data']);

$entries = $modx->lexicon->getFileTopic($_DATA['language'],$_DATA['namespace'],$_DATA['topic']);

/* get entry */
$entry = $modx->getObject('modLexiconEntry',array(
    'name' => $_DATA['name'],
    'namespace' => $_DATA['namespace'],
    'language' => $_DATA['language'],
    'topic' => $_DATA['topic'],
));
/* if entry is same as file, remove db custom */
if (!empty($entries[$_DATA['name']]) && $entries[$_DATA['name']] == $_DATA['value']) {
    if ($entry) {
        $entry->remove();
    }
    $modx->lexicon->clearCache($_DATA['language'].'/'.$_DATA['namespace'].'/'.$_DATA['topic'].'.cache.php');
} else {
    if ($entry == null) {
        $entry = $modx->newObject('modLexiconEntry');
        $entry->set('name',$_DATA['name']);
        $entry->set('namespace',$_DATA['namespace']);
        $entry->set('language',$_DATA['language']);
        $entry->set('topic',$_DATA['topic']);
    }
    $entry->set('value',$_DATA['value']);

    if ($entry->save() == false) {
        return $modx->error->failure($modx->lexicon('entry_err_save'));
    }

    /* clear cache */
    $entry->clearCache();
}

/* log manager action */
$modx->logManagerAction('lexicon_entry_update','modLexiconEntry',$entry->get('id'));

return $modx->error->success();