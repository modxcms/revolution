<?php
/**
 * Saves a Form Customization Set.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Default action.
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('set_err_ns'));
$set = $modx->getObject('modFormCustomizationSet',$scriptProperties['id']);
if ($set == null) return $modx->error->failure($modx->lexicon('set_err_nf'));

$scriptProperties['active'] = !empty($scriptProperties['active']) ? true : false;
$set->fromArray($scriptProperties);
$set->set('constraint_class','modResource');
$set->set('action',$scriptProperties['action_id']);
$set->save();

$action = $set->getOne('Action');

/* clear old rules for set */
$oldRules = $modx->getCollection('modActionDom',array(
    'set' => $set->get('id'),
));
foreach ($oldRules as $or) {
    $or->remove();
}

$newRules = array();

/* calculate field rules */
$fields = $modx->fromJSON($scriptProperties['fields']);
foreach ($fields as $field) {
    if (empty($field['visible'])) {
        $rule = $modx->newObject('modActionDom');
        $rule->set('set',$set->get('id'));
        $rule->set('action',$set->get('action'));
        $rule->set('name',$field['name']);
        $rule->set('container','modx-panel-resource');
        $rule->set('rule','fieldVisible');
        $rule->set('value',0);
        $rule->set('constraint_class',$set->get('constraint_class'));
        $rule->set('constraint_field',$set->get('constraint_field'));
        $rule->set('constraint',$set->get('constraint'));
        $rule->set('active',true);
        if ($action && $action->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }
        $rule->set('rank',5);
        $newRules[] = $rule;
    }
    if (!empty($field['label'])) {
        $rule = $modx->newObject('modActionDom');
        $rule->set('set',$set->get('id'));
        $rule->set('action',$set->get('action'));
        $rule->set('name',$field['name']);
        $rule->set('container','modx-panel-resource');
        $rule->set('rule','fieldTitle');
        $rule->set('value',$field['label']);
        $rule->set('constraint_class',$set->get('constraint_class'));
        $rule->set('constraint_field',$set->get('constraint_field'));
        $rule->set('constraint',$set->get('constraint'));
        $rule->set('active',true);
        if ($action && $action->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }
        $rule->set('rank',4);
        $newRules[] = $rule;
    }
    if (isset($field['default_value']) && $field['default_value'] != '') {
        $rule = $modx->newObject('modActionDom');
        $rule->set('set',$set->get('id'));
        $rule->set('action',$set->get('action'));
        $rule->set('name',$field['name']);
        $rule->set('container','modx-panel-resource');
        $rule->set('rule','fieldDefault');
        $rule->set('value',$field['default_value']);
        $rule->set('constraint_class',$set->get('constraint_class'));
        $rule->set('constraint_field',$set->get('constraint_field'));
        $rule->set('constraint',$set->get('constraint'));
        $rule->set('active',true);
        if ($action && $action->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }
        $rule->set('rank',0);
        $newRules[] = $rule;
    }
}

/* calculate tabs rules */
$tabs = $modx->fromJSON($scriptProperties['tabs']);
foreach ($tabs as $tab) {
    $tabField = $modx->getObject('modActionField',array(
        'action' => $action->get('id'),
        'name' => $tab['name'],
        'type' => 'tab',
    ));
    /* if creating a new tab */
    if (empty($tabField) && !empty($tab['visible'])) {
        $rule = $modx->newObject('modActionDom');
        $rule->set('set',$set->get('id'));
        $rule->set('action',$set->get('action'));
        $rule->set('name',$tab['name']);
        $rule->set('container','modx-resource-tabs');
        $rule->set('rule','tabNew');
        $rule->set('value',$tab['label']);
        $rule->set('constraint_class',$set->get('constraint_class'));
        $rule->set('constraint_field',$set->get('constraint_field'));
        $rule->set('constraint',$set->get('constraint'));
        $rule->set('active',true);
        if ($action && $action->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }
        $rule->set('rank',1);
        $newRules[] = $rule;
    } else {
    /* otherwise editing an existing one */
        if (empty($tab['visible'])) {
            $rule = $modx->newObject('modActionDom');
            $rule->set('set',$set->get('id'));
            $rule->set('action',$set->get('action'));
            $rule->set('name',$tab['name']);
            $rule->set('container','modx-resource-tabs');
            $rule->set('rule','tabVisible');
            $rule->set('value',0);
            $rule->set('constraint_class',$set->get('constraint_class'));
            $rule->set('constraint_field',$set->get('constraint_field'));
            $rule->set('constraint',$set->get('constraint'));
            $rule->set('active',true);
            if ($action && $action->get('controller') == 'resource/create') {
                $rule->set('for_parent',true);
            }
            $rule->set('rank',2);
            $newRules[] = $rule;
        }
        if (!empty($tab['label'])) {
            $rule = $modx->newObject('modActionDom');
            $rule->set('set',$set->get('id'));
            $rule->set('action',$set->get('action'));
            $rule->set('name',$tab['name']);
            $rule->set('container','modx-resource-tabs');
            $rule->set('rule','tabTitle');
            $rule->set('value',$tab['label']);
            $rule->set('constraint_class',$set->get('constraint_class'));
            $rule->set('constraint_field',$set->get('constraint_field'));
            $rule->set('constraint',$set->get('constraint'));
            $rule->set('active',true);
            if ($action && $action->get('controller') == 'resource/create') {
                $rule->set('for_parent',true);
            }
            $rule->set('rank',3);
            $newRules[] = $rule;
        }
    }
}

/* calculate TV rules */
$tvs = $modx->fromJSON($scriptProperties['tvs']);
foreach ($tvs as $tvData) {
    $tv = $modx->getObject('modTemplateVar',$tvData['id']);
    if (empty($tv)) continue;

    if (empty($tvData['visible'])) {
        $rule = $modx->newObject('modActionDom');
        $rule->set('set',$set->get('id'));
        $rule->set('action',$set->get('action'));
        $rule->set('name','tv'.$tv->get('id'));
        $rule->set('container','modx-panel-resource');
        $rule->set('rule','tvVisible');
        $rule->set('value',0);
        $rule->set('constraint_class',$set->get('constraint_class'));
        $rule->set('constraint_field',$set->get('constraint_field'));
        $rule->set('constraint',$set->get('constraint'));
        $rule->set('active',true);
        if ($action && $action->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }
        $rule->set('rank',12);
        $newRules[] = $rule;
    }
    if (!empty($tvData['label'])) {
        $rule = $modx->newObject('modActionDom');
        $rule->set('set',$set->get('id'));
        $rule->set('action',$set->get('action'));
        $rule->set('name','tv'.$tv->get('id'));
        $rule->set('container','modx-panel-resource');
        $rule->set('rule','tvTitle');
        $rule->set('value',$tvData['label']);
        $rule->set('constraint_class',$set->get('constraint_class'));
        $rule->set('constraint_field',$set->get('constraint_field'));
        $rule->set('constraint',$set->get('constraint'));
        $rule->set('active',true);
        if ($action && $action->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }
        $rule->set('rank',11);
        $newRules[] = $rule;
    }
    if ($tv->get('default_text') != $tvData['default_value']) {
        $rule = $modx->newObject('modActionDom');
        $rule->set('set',$set->get('id'));
        $rule->set('action',$set->get('action'));
        $rule->set('name','tv'.$tv->get('id'));
        $rule->set('container','modx-panel-resource');
        $rule->set('rule','tvDefault');
        $rule->set('value',$tvData['default_value']);
        $rule->set('constraint_class',$set->get('constraint_class'));
        $rule->set('constraint_field',$set->get('constraint_field'));
        $rule->set('constraint',$set->get('constraint'));
        $rule->set('active',true);
        if ($action && $action->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }
        $rule->set('rank',10);
        $newRules[] = $rule;
    }
    if (!empty($tvData['tab']) && $tvData['tab'] != 'modx-panel-resource-tv') {
        $rule = $modx->newObject('modActionDom');
        $rule->set('set',$set->get('id'));
        $rule->set('action',$set->get('action'));
        $rule->set('name','tv'.$tv->get('id'));
        $rule->set('container','modx-panel-resource');
        $rule->set('rule','tvMove');
        $rule->set('value',$tvData['tab']);
        $rule->set('constraint_class',$set->get('constraint_class'));
        $rule->set('constraint_field',$set->get('constraint_field'));
        $rule->set('constraint',$set->get('constraint'));
        $rule->set('active',true);
        if ($action && $action->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }
        /* add 20 to rank to make sure happens after tab create */
        $rank = 20+((int)$tvData['rank']);
        $rule->set('rank',$rank);
        $newRules[] = $rule;
    }
}

/* save new rules to set */
foreach ($newRules as $newRule) {
    $newRule->save();
}

return $modx->error->success();