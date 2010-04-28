<?php
/**
 * Grabs a list of templates associated with the TV
 *
 * @param integer $tv The ID of the TV
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.template.tv
 */
if (!$modx->hasPermission('view_tv')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('tv');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'templatename');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$tv = $modx->getOption('tv',$scriptProperties,false);

/* query for templates */
$c = $modx->newQuery('modTemplate');
$c->select($modx->getSelectColumns('modTemplate','modTemplate'));
$c->select('
    IF(ISNULL(`TemplateVarTemplates`.`tmplvarid`),0,1) AS `access`
');
$c->leftJoin('modTemplateVarTemplate','TemplateVarTemplates',array(
    '`modTemplate`.`id` = `TemplateVarTemplates`.`templateid`',
    'TemplateVarTemplates.tmplvarid' => $tv,
));
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$templates = $modx->getCollection('modTemplate',$c);

$count = $modx->getCount('modTemplate');

/* iterate through templates */
$list = array();
foreach ($templates as $template) {
    $templateArray = $template->toArray();
    $templateArray['access'] = (boolean)$template->get('access');
    unset($templateArray['content']);
    $list[] = $templateArray;
}

return $this->outputArray($list,$count);