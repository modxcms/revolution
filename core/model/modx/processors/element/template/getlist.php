<?php
/**
 * Grabs a list of templates.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.template
 */
$modx->lexicon->load('template');

/* get default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'templatename');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$combo = $modx->getOption('combo',$scriptProperties,false);

/* query templates */
$c = $modx->newQuery('modTemplate');
$c->leftJoin('modCategory','Category');
$c->select(array('modTemplate.*','Category.category'));

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);

$templates = $modx->getCollection('modTemplate',$c);
$count = $modx->getCount('modTemplate');

/* iterate through templates */
$list = array();
if ($combo) {
    $empty = array(
        'id' => 0,
        'templatename' => $modx->lexicon('template_empty'),
        'description' => '',
        'editor_type' => 0,
        'icon' => '',
        'template_type' => 0,
        'content' => '',
        'locked' => false,
    );
    $empty['category'] = '';
    $list[] = $empty;
}
foreach ($templates as $template) {
    if (!$template->checkPolicy('list')) continue;
    
    $templateArray = $template->toArray();
    $templateArray['category'] = $template->get('category') != null ? $template->get('category') : '';
    unset($templateArray['content']);
    $list[] = $templateArray;
}

return $this->outputArray($list,$count);