<?php
/**
 * Gets a list of TVs, marking ones associated with the template.
 *
 * @param integer $template (optional) The template which the TVs are associated
 * to.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.template.tv
 */
$modx->lexicon->load('template');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'rank';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modTemplateVar');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);

$tvs = $modx->getCollection('modTemplateVar',$c);
$count = $modx->getCount('modTemplateVar');

$ts = array();
foreach ($tvs as $tv) {
    if (isset($_REQUEST['template'])) {
        $tvt = $modx->getObject('modTemplateVarTemplate',array(
            'templateid' => $_REQUEST['template'],
            'tmplvarid' => $tv->get('id'),
        ));
    } else $tvt = null;

    if ($tvt == null) {
        $tv->set('access',false);
        $tv->set('rank',0);
    } else {
        $tv->set('access',true);
        $tv->set('rank',$tvt->get('rank'));
    }
	$ts[] = $tv->toArray();
}
return $this->outputArray($ts,$count);