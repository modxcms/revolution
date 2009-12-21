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
if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('tv');

/* setup default properties */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'templatename');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');
$tv = $modx->getOption('tv',$_REQUEST,false);

/* query for templates */
$c = $modx->newQuery('modTemplate');
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$templates = $modx->getCollection('modTemplate',$c);
$count = $modx->getCount('modTemplate');

/* iterate through templates */
$list = array();
foreach ($templates as $template) {
    if ($tv) {
        $tvt = $modx->getObject('modTemplateVarTemplate',array(
            'tmplvarid' => $tv,
            'templateid' => $template->get('id'),
        ));
    } else $tvt = null;

    if ($tvt != null) {
        $template->set('access',true);
        $template->set('rank',$tvt->get('rank'));
    } else {
        $template->set('access',false);
        $template->set('rank','');
    }
    $templateArray = $template->toArray();
    unset($templateArray['content']);

    $templateArray['menu'] = array();

    $list[] = $templateArray;
}

return $this->outputArray($list,$count);