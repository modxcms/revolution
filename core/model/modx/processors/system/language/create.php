<?php
/**
 * Create a lexicon language
 *
 * @param string $name The name of the language, in IANA code format
 *
 * @package modx
 * @subpackage processors.system.language
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('languages')) return $modx->error->failure($modx->lexicon('permission_denied'));

$language = $modx->newObject('modLexiconLanguage');
$language->set('name',$_POST['name']);

if ($language->save() === false) {
    return $modx->error->failure($modx->lexicon('language_err_create'));
}

return $modx->error->success();