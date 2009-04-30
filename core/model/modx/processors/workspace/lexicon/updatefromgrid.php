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

if (!isset($_DATA['id'])) return $modx->error->failure($modx->lexicon('entry_err_ns'));
$entry = $modx->getObject('modLexiconEntry',$_DATA['id']);
if ($entry == null) {
    return $modx->error->failure(sprintf($modx->lexicon('entry_err_nfs'),$_DATA['id']));
}

if (!isset($_DATA['name']) || $_DATA['name'] == '') {
    return $modx->error->failure($modx->lexicon('entry_err_ns_name'));
}

$entry->set('name',$_DATA['name']);
$entry->set('value',$_DATA['value']);

if ($entry->save() == false) {
    return $modx->error->failure($modx->lexicon('entry_err_save'));
}

$entry->clearCache();

/* log manager action */
$modx->logManagerAction('lexicon_entry_update','modLexiconEntry',$entry->get('id'));

return $modx->error->success();