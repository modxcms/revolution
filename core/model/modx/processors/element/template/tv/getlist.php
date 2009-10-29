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
if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('template');

/* get default properties */
$isLimit = empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,20);
$sort = $modx->getOption('sort',$_REQUEST,'rank');
$sortAlias = $modx->getOption('sort',$_REQUEST,'modTemplateVar');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$template = $modx->getOption('template',$_REQUEST,false);

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'rank';
if (!isset($_REQUEST['sortAlias'])) $_REQUEST['sortAlias'] = 'modTemplateVar';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modTemplateVar');

if ($template) {
    $c->leftJoin('modTemplateVarTemplate','modTemplateVarTemplate','
        modTemplateVarTemplate.tmplvarid = modTemplateVar.id
    AND modTemplateVarTemplate.templateid = '.$template.'
    ');
    $c->select('modTemplateVar.*,
        IF(ISNULL(modTemplateVarTemplate.tmplvarid),0,1) AS access,
        modTemplateVarTemplate.rank AS rank
    ');
}
$count = $modx->getCount('modTemplateVar',$c);

$c->sortby($sortAlias.'.'.$sort,$dir);
$c->limit($limit,$start);
$tvs = $modx->getCollection('modTemplateVar',$c);

/* iterate through tvs */
$list = array();
foreach ($tvs as $tv) {
    $tv->set('access',$tv->get('access') ? 1 : 0);
	$list[] = $tv->toArray();
}
return $this->outputArray($list,$count);