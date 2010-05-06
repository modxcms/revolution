<?php
/**
 * Updates a lexicon entry from a grid
 *
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('lexicon');

$entries = $modx->lexicon->getFileTopic($scriptProperties['language'],$scriptProperties['namespace'],$scriptProperties['topic']);

/* get entry */
$entry = $modx->getObject('modLexiconEntry',array(
    'name' => $scriptProperties['name'],
    'namespace' => $scriptProperties['namespace'],
    'language' => $scriptProperties['language'],
    'topic' => $scriptProperties['topic'],
));
if ($entry) {
    $entry->remove();
    $modx->lexicon->clearCache($scriptProperties['language'].'/'.$scriptProperties['namespace'].'/'.$scriptProperties['topic'].'.cache.php');
}

/* log manager action */
$modx->logManagerAction('lexicon_entry_update','modLexiconEntry',$entry->get('id'));

return $modx->error->success();