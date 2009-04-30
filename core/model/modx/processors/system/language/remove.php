<?php
/**
 * Removes a language
 *
 * @param string $name The name of the language
 *
 * @package modx
 * @subpackage processors.system.language
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('languages')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['name'])) return $modx->error->failure($modx->lexicon('language_err_ns'));
$language = $modx->getObject('modLexiconLanguage',$_POST['name']);
if ($language == null) return $modx->error->failure($modx->lexicon('language_err_nf'));

if ($language->get('name') == 'en') return $modx->error->failure($modx->lexicon('language_err_remove_english'));

if ($language->remove() === false) {
    return $modx->error->failure($modx->lexicon('language_err_remove'));
}

return $modx->error->success();