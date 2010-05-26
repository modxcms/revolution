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
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'action');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

/* query for rules */
$c = $modx->newQuery('modActionDom');
$c->innerJoin('modAction','Action');
$c->leftJoin('modAccessActionDom','Access');
$count = $modx->getCount('modActionDom',$c);
$c->select(array(
    'modActionDom.*',
    'Action.controller',
    'Access.principal',
    'Access.principal_class',
));

$c->sortby($sort,$dir);
if ($limit) $c->limit($limit,$start);

$rules = $modx->getCollection('modActionDom', $c);

/* iterate through rules */
$data = array();
$canEdit = $modx->hasPermission('save');
$canRemove = $modx->hasPermission('remove');
foreach ($rules as $rule) {
    $ruleArray = $rule->toArray();

    if ($ruleArray['principal'] == null) $ruleArray['principal'] = 0;
    if ($ruleArray['principal_class'] == null) $ruleArray['principal_class'] = '';

    $ruleArray['menu'] = array();
    if ($canEdit) {
        $ruleArray['menu'][] = array(
            'text' => $modx->lexicon('edit'),
            'handler' => 'this.updateRule',
        );
        $ruleArray['menu'][] = array(
            'text' => $modx->lexicon('duplicate'),
            'handler' => 'this.duplicateRule',
        );
        $ruleArray['menu'][] = '-';
    }
    if ($canRemove) {
        $ruleArray['menu'][] = array(
            'text' => $modx->lexicon('remove'),
            'handler' => 'this.confirm.createDelegate(this,["remove","rule_remove_confirm"])',
        );
    }
    $data[] = $ruleArray;
}

return $this->outputArray($data,$count);