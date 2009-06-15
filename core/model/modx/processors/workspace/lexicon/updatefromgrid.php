<?php
/**
 * Updates a lexicon entry from a grid
 *
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

/* get entry */
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('entry_err_ns'));
$entry = $modx->getObject('modLexiconEntry',$_DATA['id']);
if ($entry == null) {
    return $modx->error->failure($modx->lexicon('entry_err_nfs',array('key' => $_DATA['id'])));
}

/* validation */
if (empty($_DATA['name'])) {
    return $modx->error->failure($modx->lexicon('entry_err_ns_name'));
}

/* set and save entry */
$entry->fromArray($_DATA);
if ($entry->save() == false) {
    return $modx->error->failure($modx->lexicon('entry_err_save'));
}

/* clear cache */
$entry->clearCache();

/* log manager action */
$modx->logManagerAction('lexicon_entry_update','modLexiconEntry',$entry->get('id'));

return $modx->error->success();