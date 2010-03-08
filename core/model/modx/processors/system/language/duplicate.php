<?php
/**
 * Duplicates a lexicon language
 *
 * @param string $name The name of the language, in IANA code format
 * @param string $new_name The new language name, in IANA code format
 * @param string $recursive If true, will duplicate all entries associated with
 * old language
 *
 * @package modx
 * @subpackage processors.system.language
 */
if (!$modx->hasPermission('languages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('lexicon');

if (empty($scriptProperties['new_name'])) return $modx->error->failure($modx->lexicon('language_err_ns_new_name'));

/* get language */
if (empty($scriptProperties['name'])) return $modx->error->failure($modx->lexicon('language_err_ns'));
$language = $modx->getObject('modLexiconLanguage',array('name' => $scriptProperties['name']));
if (empty($language)) return $modx->error->failure($modx->lexicon('language_err_nf'));

/* create language */
$duplicatedLanguage = $modx->getObject('modLexiconLanguage',array(
    'name' => $scriptProperties['new_name'],
));
if (empty($duplicatedLanguage)) {
    $duplicatedLanguage = $modx->newObject('modLexiconLanguage');
    $duplicatedLanguage->set('name',$scriptProperties['new_name']);
}

/* if duplicating topics/entries */
if (!empty($scriptProperties['recursive'])) {
    $entries = $modx->getCollection('modLexiconEntry',array(
        'language' => $language->get('name'),
    ));
    $newEntries = array();
    foreach ($entries as $entry) {
        $newEntry = $modx->newObject('modLexiconEntry');
        $newEntry->fromArray($entry->toArray());
        $newEntry->set('language',$duplicatedLanguage->get('name'));
        $newEntries[$newEntry->get('name')] = $newEntry;
    }
    $duplicatedLanguage->addMany($newEntries);
}

/* save language */
if ($duplicatedLanguage->save() === false) {
    return $modx->error->failure($modx->lexicon('language_err_duplicate'));
}

return $modx->error->success('',$duplicatedLanguage);