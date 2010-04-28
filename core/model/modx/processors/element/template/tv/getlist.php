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
if (!$modx->hasPermission(array('view_tv' => true,'view_template' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$modx->lexicon->load('template');

/* get default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sortAlias = $modx->getOption('sortAlias',$scriptProperties,'modTemplateVar');
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$template = $modx->getOption('template',$scriptProperties,false);

$c = $modx->newQuery('modTemplateVar');
$count = $modx->getCount('modTemplateVar',$c);
$c->select($modx->getSelectColumns('modTemplateVar','modTemplateVar'));
if ($template) {
    $c->leftJoin('modTemplateVarTemplate','modTemplateVarTemplate','
        `modTemplateVarTemplate`.`tmplvarid` = `modTemplateVar`.`id`
    AND `modTemplateVarTemplate`.`templateid` = '.$template.'
    ');
    $c->select('
        IF(ISNULL(`modTemplateVarTemplate`.`tmplvarid`),0,1) AS `access`,
        `modTemplateVarTemplate`.`rank` AS `tv_rank`
    ');
}
if ($sort != 'tv_rank') {
    $c->sortby($sortAlias.'.'.$sort,$dir);
} else {
    $c->where(array('modTemplateVarTemplate.rank:!=' => null));
    $c->sortby($sort,$dir);
}
$c->limit($limit,$start);
$tvs = $modx->getCollection('modTemplateVar',$c);

/* iterate through tvs */
$list = array();
foreach ($tvs as $tv) {
    $tvArray = $tv->get(array('id','name','description','tv_rank'));
    $tvArray['access'] = (boolean)$tv->get('access');
    
    $list[] = $tvArray;
}
return $this->outputArray($list,$count);