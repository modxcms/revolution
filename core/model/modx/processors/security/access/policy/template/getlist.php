<?php
/**
 * Gets a list of policy templates.
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
 * @subpackage processors.security.access.policy.template
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
$c = $modx->newQuery('modAccessPolicyTemplate');
$c->innerJoin('modAccessPolicyTemplateGroup','TemplateGroup');
$count = $modx->getCount('modAccessPolicyTemplate',$c);


$subc = $modx->newQuery('modAccessPermission');
$subc->select('COUNT(modAccessPermission.id)');
$subc->where(array(
    'modAccessPermission.template = modAccessPolicyTemplate.id',
));
$subc->prepare();
$c->select(array(
    'modAccessPolicyTemplate.*',
    'TemplateGroup.name AS template_group_name',
));
$c->select('('.$subc->toSql().') AS '.$modx->escape('total_permissions'));

if($sort == 'name') {
    $sort = $modx->escape('TemplateGroup') . '.' . $modx->escape('name');
}
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$templates = $modx->getCollection('modAccessPolicyTemplate', $c);

$core = array('ResourceTemplate','ObjectTemplate','AdministratorTemplate','ElementTemplate');

foreach ($templates as $key => $template) {
    $templateArray = $template->toArray();
    $cls = 'pedit';
    if (!in_array($template->get('name'),$core)) {
        $cls .= ' premove';
    }
    $templateArray['cls'] = $cls;

    $data[] = $templateArray;
}

return $this->outputArray($data,$count);