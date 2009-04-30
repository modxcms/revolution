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
$modx->lexicon->load('lexicon');

if (isset($_REQUEST['limit'])) $limit = true;
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;

$c = $modx->newQuery('modLexiconLanguage');
$c->where($wa);
$c->sortby('name', 'ASC');
if ($limit) $c->limit($_REQUEST['limit'],$_REQUEST['start']);
$languages = $modx->getCollection('modLexiconLanguage',$c);
$count = $modx->getCount('modLexiconLanguage',$wa);

$ps = array();
foreach ($languages as $language) {
    $pa = $language->toArray();

    if ($language->get('name') != 'en') {
    $pa['menu'] = array(
        array(
            'text' => $modx->lexicon('language_remove'),
            'handler' => 'this.remove.createDelegate(this,["language_remove_confirm"])',
        ),
    );
    }
    $ps[] = $pa;
}

return $this->outputArray($ps,$count);