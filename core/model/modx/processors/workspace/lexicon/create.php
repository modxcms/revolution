<?php
/**
 * Updates a lexicon entry from a grid
 *
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('lexicon');

/* get entry */
$entry = $modx->getObject('modLexiconEntry',array(
    'name' => $scriptProperties['name'],
    'namespace' => $scriptProperties['namespace'],
    'language' => $scriptProperties['language'],
    'topic' => $scriptProperties['topic'],
));
if ($entry) return $modx->error->failure($modx->lexicon('entry_err_ae'));

$entry = $modx->newObject('modLexiconEntry');
$entry->fromArray($scriptProperties);

if ($entry->save() == false) {
    return $modx->error->failure($modx->lexicon('entry_err_save'));
}

/* log manager action */
$modx->logManagerAction('lexicon_entry_create','modLexiconEntry',$entry->get('id'));

return $modx->error->success();