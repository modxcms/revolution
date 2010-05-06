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
$namespace = !empty($scriptProperties['namespace']) ? $scriptProperties['namespace'] : 'core';

$languages = $modx->lexicon->getLanguageList($namespace);
$count = count($languages);
if ($isLimit) {
    $languages = array_slice($languages,$start,$limit,true);
}

/* loop through */
$list = array();
foreach ($languages as $language) {
    $list[] = array(
        'name' => $language,
    );
}

return $this->outputArray($list,$count);