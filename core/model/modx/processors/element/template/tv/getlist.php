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

if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'rank';
if (!isset($_REQUEST['sortAlias'])) $_REQUEST['sortAlias'] = 'modTemplateVar';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modTemplateVar');

if (!empty($_REQUEST['template'])) {
    $c->leftJoin('modTemplateVarTemplate','modTemplateVarTemplate','
        modTemplateVarTemplate.tmplvarid = modTemplateVar.id
    AND modTemplateVarTemplate.templateid = '.$_REQUEST['template'].'
    ');
    $c->select('modTemplateVar.*,
        IF(ISNULL(modTemplateVarTemplate.tmplvarid),0,1) AS access,
        modTemplateVarTemplate.rank AS rank
    ');
}

$c->sortby($_REQUEST['sortAlias'].'.'.$_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);

$tvs = $modx->getCollection('modTemplateVar',$c);
$count = $modx->getCount('modTemplateVar');

$ts = array();
foreach ($tvs as $tv) {
    $tv->set('access',$tv->get('access') ? 1 : 0);
	$ts[] = $tv->toArray();
}
return $this->outputArray($ts,$count);