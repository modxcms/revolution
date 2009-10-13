<?php
/**
 * Create a lexicon language
 *
 * @param string $name The name of the language, in IANA code format
 *
 * @package modx
 * @subpackage processors.system.language
 */
if (!$modx->hasPermission('languages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('lexicon');

/* validate fields */
if (empty($_POST['name'])) return $modx->error->failure($modx->lexicon('language_err_ns'));

/* check if name already exists */
$alreadyExists = $modx->getObject('modLexiconLanguage',array('name' => $_POST['name']));
if ($alreadyExists) return $modx->error->failure($modx->lexicon('language_err_ae'));

/* create language */
$language = $modx->newObject('modLexiconLanguage');
$language->set('name',$_POST['name']);

/* save language */
if ($language->save() === false) {
    return $modx->error->failure($modx->lexicon('language_err_create'));
}

return $modx->error->success('',$language);