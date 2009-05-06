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

$limit = isset($_REQUEST['limit']) ? true : false;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'templatename';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';


$c = $modx->newQuery('modTemplate');
$c->leftJoin('modCategory','modCategory');
$c->select('modTemplate.*,modCategory.category AS category');

$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) { $c->limit($_REQUEST['limit'],$_REQUEST['start']); }

$templates = $modx->getCollection('modTemplate',$c);
$count = $modx->getCount('modTemplate');

$list = array();
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
    $list[] = $empty;
}
foreach ($templates as $template) {
	$array = $template->toArray();
	$array['category'] = $template->get('category') != null ? $template->get('category') : '';
	$list[] = $array;
}

return $this->outputArray($list,$count);