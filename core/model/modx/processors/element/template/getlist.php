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

/* if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20; */
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'templatename';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modTemplate');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);

if (isset($_REQUEST['limit'])) {
	$c->limit($_REQUEST['limit'],$_REQUEST['start']);
}

$templates = $modx->getCollection('modTemplate',$c);
$count = $modx->getCount('modTemplate');

$cs = array();
if (isset($_REQUEST['combo'])) {
    $empty = array(
        'id' => 0,
        'templatename' => '(empty)',
        'description' => '',
        'editor_type' => 0,
        'icon' => '',
        'template_type' => 0,
        'content' => '',
        'locked' => false,
    );
    $empty['category'] = '';
    $cs[] = $empty;
}
foreach ($templates as $template) {
	$cat = $template->getOne('modCategory');
	$ca = $template->toArray();
	$ca['category'] = $cat ? $cat->get('category') : '';
	$cs[] = $ca;
}

return $this->outputArray($cs,$count);