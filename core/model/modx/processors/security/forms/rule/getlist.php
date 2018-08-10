<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

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
$sort = $modx->getOption('sort',$scriptProperties,'action,rank');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$search = $modx->getOption('search',$scriptProperties,'');
$controller = $modx->getOption('controller',$scriptProperties,'');
$rule = $modx->getOption('rule',$scriptProperties,'');

/* query for rules */
$c = $modx->newQuery('modActionDom');
$c->innerJoin('modAction','Action');
$c->leftJoin('modAccessActionDom','Access');

if (!empty($search)) {
    $c->where(array(
        'modActionDom.description:LIKE' => '%'.$search.'%',
        'OR:modActionDom.value:LIKE' => '%'.$search.'%',
    ),null,2);
}
if (!empty($controller)) {
    $c->where(array(
        'action' => $controller,
    ));
}
if (!empty($rule)) {
    $c->where(array(
        'rule' => $rule,
    ));
}

$count = $modx->getCount('modActionDom',$c);
$c->select($modx->getSelectColumns('modActionDom'));
$c->select($modx->getSelectColumns('modAction', 'Action', '', array('controller')));
$c->select($modx->getSelectColumns('modAccessActionDom', 'Access', '', array('principal', 'principal_class')));

$c->sortby($sort,$dir);
if ($limit) $c->limit($limit,$start);

$rules = $modx->getCollection('modActionDom', $c);

/* iterate through rules */
$data = array();
$canEdit = $modx->hasPermission('save');
$canRemove = $modx->hasPermission('remove');
foreach ($rules as $rule) {
    $ruleArray = $rule->toArray();

    $ruleArray['perm'] = array();
    if ($canEdit) $ruleArray['perm'][] = 'pedit';
    if ($canRemove) $ruleArray['perm'][] = 'premove';

    if ($ruleArray['principal'] == null) $ruleArray['principal'] = 0;
    if ($ruleArray['principal_class'] == null) $ruleArray['principal_class'] = '';

    $data[] = $ruleArray;
}

return $this->outputArray($data,$count);
