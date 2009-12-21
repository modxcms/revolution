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
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'name');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');

/* build query */
$c = $modx->newQuery('modAccessPolicy');
$count = $modx->getCount('modAccessPolicy',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$policies = $modx->getCollection('modAccessPolicy', $c);

/* iterate */
$data = array();
if (isset($_REQUEST['combo'])) {
    $data[] = array(
        'id' => ''
        ,'name' => ' (no policy) '
    );
}

foreach ($policies as $key => $policy) {
    $policyArray = $policy->toArray();
    $policyArray['menu'] = array(
        array(
            'text' => $modx->lexicon('edit'),
            'handler' => 'this.editPolicy',
        ),
        array(
            'text' => $modx->lexicon('duplicate'),
            'handler' => 'this.confirm.createDelegate(this,["duplicate","policy_duplicate_confirm"])',
        ),
        '-',
        array(
            'text' => $modx->lexicon('remove'),
            'handler' => 'this.confirm.createDelegate(this,["remove","policy_remove_confirm"])',
        ),
    );
    $data[] = $policyArray;
}

return $this->outputArray($data,$count);