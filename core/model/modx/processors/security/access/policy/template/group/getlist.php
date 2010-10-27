<?php
/**
 * Gets a list of policy template groups.
 *
 * @param boolean $combo (optional) If true, will append a 'no policy' row to
 * the beginning.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Default
 *
 * @package modx
 * @subpackage processors.security.access.policy.template.group
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* build query */
$c = $modx->newQuery('modAccessPolicyTemplateGroup');
$count = $modx->getCount('modAccessPolicyTemplateGroup',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$templateGroups = $modx->getCollection('modAccessPolicyTemplateGroup', $c);

foreach ($templateGroups as $key => $templateGroup) {
    $templateGroupArray = $templateGroup->toArray();
    $cls = 'pedit';
    $templateGroupArray['cls'] = $cls;

    $data[] = $templateGroupArray;
}

return $this->outputArray($data,$count);