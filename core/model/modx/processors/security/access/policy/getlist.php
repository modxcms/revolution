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
$modx->lexicon->load('policy');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

$limit = isset($_REQUEST['limit']);
if (empty($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (empty($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (empty($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (empty($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modAccessPolicy');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) $c->limit($_REQUEST['limit'],$_REQUEST['start']);

$policies = $modx->getCollection('modAccessPolicy', $c);

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

return $this->outputArray($data);