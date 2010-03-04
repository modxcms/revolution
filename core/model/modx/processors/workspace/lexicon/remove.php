<?php
/**
 * Removes a lexicon entry
 *
 * @param integer $id The ID of the entry
 *
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get entry */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('entry_err_ns'));
$entry = $modx->getObject('modLexiconEntry',$scriptProperties['id']);
if ($entry == null) {
    return $modx->error->failure(sprintf($modx->lexicon('entry_err_nfs'),$scriptProperties['id']));
}

/* remove entry */
if ($entry->remove() === false) {
    return $modx->error->failure($modx->lexicon('entry_err_save'));
}

/* clear cache */
$entry->clearCache();

/* log manager action */
$modx->logManagerAction('lexicon_entry_remove','modLexiconEntry',$entry->get('id'));

return $modx->error->success();