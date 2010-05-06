<?php
/**
 * Gets a list of lexicon entries
 *
 * @param string $namespace (optional) If set, will filter by namespace.
 * Defaults to core.
 * @param integer $topic (optional) If set, will filter by this topic
 * @param string $language (optional) If set, will filter by language. Defaults
 * to en.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 *
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('lexicon');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$language = !empty($scriptProperties['language']) ? $scriptProperties['language'] : 'en';
$namespace = !empty($scriptProperties['namespace']) ? $scriptProperties['namespace'] : 'core';
$topic = !empty($scriptProperties['topic']) ? $scriptProperties['topic'] : 'default';

$where = array(
    'namespace' => $namespace,
    'topic' => $topic,
    'language' => $language,
);

/* first get file-based lexicon */
$entries = $modx->lexicon->getFileTopic($language,$namespace,$topic);

/* if searching */
if (!empty($scriptProperties['search'])) {
    function parseArray($needle,array $haystack = array()) {
        if (!is_array($haystack)) return false;
        $results = array();
        foreach($haystack as $key=>$value) {
            if (strpos($key, $needle)!==false || strpos($value,$needle) !== false) {
                $results[$key] = $value;
            }
        }
        return $results;
    }

    $entries = parseArray($scriptProperties['search'],$entries);
    $where[] = array(
        'name:LIKE' => '%'.$scriptProperties['search'].'%',
        'OR:value:LIKE' => '%'.$scriptProperties['search'].'%',
    );
}
$count = count($entries);
$entries = array_slice($entries,$start,$limit,true);

/* setup query */
$c = $modx->newQuery('modLexiconEntry');
$c->where($where);
$c->sortby('name','ASC');
$results = $modx->getCollection('modLexiconEntry',$c);
$dbEntries = array();
foreach ($results as $r) {
    $dbEntries[$r->get('name')] = $r->toArray();
}

/* loop through */
$list = array();
foreach ($entries as $name => $value) {
    $entryArray = array(
        'name' => $name,
        'value' => $value,
        'namespace' => $namespace,
        'topic' => $topic,
        'language' => $language,
        'createdon' => null,
        'editedon' => null,
        'overridden' => 0,
    );
    /* if override in db, load */
    if (array_key_exists($name,$dbEntries)) {
        $entryArray = array_merge($entryArray,$dbEntries[$name]);
        $entryArray['editedon'] = $entryArray['editedon'] == '0000-00-00 00:00:00'
                               || $entryArray['editedon'] == null
            ? ''
            : strftime('%b %d, %Y %I:%M %p',strtotime($entryArray['editedon']));
        $entryArray['overridden'] = 1;
    }
    $list[] = $entryArray;
}

return $this->outputArray($list,$count);