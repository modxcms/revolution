<?php
/**
 * Grabs a list of lexicon languages
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 *
 * @package modx
 * @subpackage processors.system.language
 */
if (!$modx->hasPermission('languages')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('lexicon');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

$c = $modx->newQuery('modLexiconLanguage');
$count = $modx->getCount('modLexiconLanguage',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);

$languages = $modx->getCollection('modLexiconLanguage',$c);

$list = array();
foreach ($languages as $language) {
    $languageArray = $language->toArray();

    $languageArray['menu'] = array();
    $languageArray['menu'][] = array(
        'text' => $modx->lexicon('language_duplicate'),
        'handler' => 'this.duplicateLanguage',
    );
    if ($language->get('name') != 'en') {
        $languageArray['menu'][] = '-';
        $languageArray['menu'][] = array(
            'text' => $modx->lexicon('language_remove'),
            'handler' => 'this.remove.createDelegate(this,["language_remove_confirm"])',
        );
    }
    $list[] = $languageArray;
}

return $this->outputArray($list,$count);