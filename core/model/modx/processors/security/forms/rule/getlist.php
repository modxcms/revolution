<?php
/**
 * Gets a list of action dom rules.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Default action.
 *
 * @package modx
 * @subpackage processors.security.forms.rule
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');


$limit = isset($_REQUEST['limit']);
if (empty($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (empty($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (empty($_REQUEST['sort'])) $_REQUEST['sort'] = 'action';
if (empty($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modActionDom');
$c->innerJoin('modAction','Action');
$c->leftJoin('modAccessActionDom','Access');
$count = $modx->getCount('modActionDom',$c);

$c->select('modActionDom.*,
    Action.controller AS controller,
    Access.principal AS principal,
    Access.principal_class AS principal_class
');

$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) $c->limit($_REQUEST['limit'],$_REQUEST['start']);

$rules = $modx->getCollection('modActionDom', $c);

$data = array();
foreach ($rules as $rule) {
    $ruleArray = $rule->toArray();

    if ($ruleArray['principal'] == null) $ruleArray['principal'] = 0;
    if ($ruleArray['principal_class'] == null) $ruleArray['principal_class'] = '';

    $ruleArray['menu'] = array(
        array(
            'text' => $modx->lexicon('edit'),
            'handler' => 'this.updateRule',
        ),
        '-',
        array(
            'text' => $modx->lexicon('remove'),
            'handler' => 'this.confirm.createDelegate(this,["remove","rule_remove_confirm"])',
        ),
    );
    $data[] = $ruleArray;
}

return $this->outputArray($data,$count);