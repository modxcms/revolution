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

if (!$modx->hasPermission('languages')) return $modx->error->failure($modx->lexicon('permission_denied'));

$limit = !empty($_REQUEST['limit']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modLexiconLanguage');

$c->sortby($_REQUEST['sort'], $_REQUEST['dir']);
if ($limit) $c->limit($_REQUEST['limit'],$_REQUEST['start']);

$languages = $modx->getCollection('modLexiconLanguage',$c);
$count = $modx->getCount('modLexiconLanguage');

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