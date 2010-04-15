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
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* build query */
$c = $modx->newQuery('modAccessPolicy');
$count = $modx->getCount('modAccessPolicy',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$policies = $modx->getCollection('modAccessPolicy', $c);

/* iterate */
$data = array();
if (isset($scriptProperties['combo'])) {
    $data[] = array(
        'id' => ''
        ,'name' => ' (no policy) '
    );
}

foreach ($policies as $key => $policy) {
    $policyArray = $policy->toArray();

    $menu = array();
    $menu[] = array(
        'text' => $modx->lexicon('edit'),
        'handler' => 'this.editPolicy',
    );
    $menu[] = array(
        'text' => $modx->lexicon('duplicate'),
        'handler' => 'this.confirm.createDelegate(this,["duplicate","policy_duplicate_confirm"])',
    );
    /* dont allow user to remove resource/admin policies */
    if ($policy->get('name') != 'Resource' && $policy->get('name') != 'Administrator') {
        $menu[] = '-';
        $menu[] = array(
            'text' => $modx->lexicon('remove'),
            'handler' => 'this.confirm.createDelegate(this,["remove","policy_remove_confirm"])',
        );
    }

    $policyArray['menu'] = $menu;
    $data[] = $policyArray;
}

return $this->outputArray($data,$count);