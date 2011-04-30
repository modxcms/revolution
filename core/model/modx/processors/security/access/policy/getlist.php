<?php
/**
 * Gets a list of policies.
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
 * @subpackage processors.security.access.policy
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('policy');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$sortAlias = $modx->getOption('sortAlias',$scriptProperties,'modAccessPolicy');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$group = $modx->getOption('group',$scriptProperties,false);

/* build query */
$c = $modx->newQuery('modAccessPolicy');
$c->innerJoin('modAccessPolicyTemplate','Template');
if (!empty($group)) {
    $group = explode(',',$group);
    $c->innerJoin('modAccessPolicyTemplateGroup','TemplateGroup','TemplateGroup.id = Template.template_group');
    $c->where(array(
        'TemplateGroup.name:IN' => $group,
    ));
}
$count = $modx->getCount('modAccessPolicy',$c);

$subc = $modx->newQuery('modAccessPermission');
$subc->select('COUNT(modAccessPermission.id)');
$subc->where(array(
    'modAccessPermission.template = Template.id',
));
$subc->prepare();
$c->select(array(
    'modAccessPolicy.*',
    'Template.name AS template_name',
));
$c->select('('.$subc->toSql().') AS '.$modx->escape('total_permissions'));


$c->sortby($sortAlias.'.'.$sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$c->prepare();
$policies = $modx->getCollection('modAccessPolicy', $c);

/* iterate */
$list = array();
if (isset($scriptProperties['combo'])) {
    $list[] = array(
        'id' => '',
        'name' => $modx->lexicon('no_policy_option'),
    );
}

$core = array('Resource','Object','Administrator','Load Only','Load, List and View');

foreach ($policies as $key => $policy) {
    $policyArray = $policy->toArray();
    $cls = 'pedit';
    if (!in_array($policy->get('name'),$core)) {
        $cls .= ' premove';
    }
    $policyArray['cls'] = $cls;
    if (!empty($policyArray['total_permissions'])) {
        $data = $policy->get('data');
        $ct = 0;
        foreach ($data as $k => $v) {
            if (!empty($v)) $ct++;
        }
        $policyArray['active_permissions'] = $ct;
        $policyArray['active_of'] = $modx->lexicon('active_of',array(
            'active' => $policyArray['active_permissions'],
            'total' => $policyArray['total_permissions'],
        ));
    }

    unset($policyArray['data']);
    $list[] = $policyArray;
}

return $this->outputArray($list,$count);