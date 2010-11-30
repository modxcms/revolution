<?php
/**
 * Import a Form Customization Set from an XML file
 *
 * @package modx
 * @subpackage processors.security.forms.set
 */
if (!$modx->hasPermission('customize_forms')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('formcustomization');

if (!function_exists('simplexml_load_string')) {
    return $modx->error->failure($modx->lexicon('simplexml_err_nf'));
}

/* get profile */
if (empty($scriptProperties['profile'])) return $modx->error->failure($modx->lexicon('profile_err_ns'));
$profile = $modx->getObject('modFormCustomizationProfile',$scriptProperties['profile']);
if ($profile == null) return $modx->error->failure($modx->lexicon('profile_err_nf'));

/* verify file exists */
if (!isset($scriptProperties['file'])) return $modx->error->failure($modx->lexicon('set_import_err_upload'));
$_FILE = $scriptProperties['file'];
if ($_FILE['error'] != 0) return $modx->error->failure($modx->lexicon('set_import_err_upload'));
$o = file_get_contents($_FILE['tmp_name']);
if (empty($o)) return $modx->error->failure($modx->lexicon('set_import_err_upload'));

/* verify and load xml */
$xml = @simplexml_load_string($o);
if (empty($xml)) return $modx->error->failure($modx->lexicon('set_import_err_xml'));

/* create set object */
$set = $modx->newObject('modFormCustomizationSet');
$set->set('profile',$profile->get('id'));
$set->set('description',(string)$xml->description);
$set->set('constraint_field',(string)$xml->constraint_field);
$set->set('constraint',(string)$xml->constraint);
$set->set('constraint_class','modResource');
$set->set('active',(int)$xml->active);

/* set action */
$action = $modx->getObject('modAction',array(
    'controller' => (string)$xml->action,
    'namespace' => 'core',
));
if (empty($action)) return $modx->error->failure($modx->lexicon('set_import_action_err_nf'));
$set->set('action',$action->get('id'));

/* set template */
$templatePk = (string)$xml->template;
if (!empty($templatePk)) {
    $template = $modx->getObject('modTemplate',array(
        'templatename' => $templatePk,
    ));
    if (empty($template)) return $modx->error->failure($modx->lexicon('set_import_template_err_nf'));
    $set->set('template',$template->get('id'));
} else {
    $set->set('template',0);
}

$rules = array();

/* set fields FC rules */
foreach ($xml->fields->field as $field) {
    $visible = (int)$field->visible;
    if (empty($visible)) {
        $rule = $modx->newObject('modActionDom');
        $rule->set('action',$action->get('id'));
        $rule->set('name',(string)$field->name);
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
        $rules[] = $rule;
    }
    $label = (string)$field->label;
    if (!empty($label)) {
        $rule = $modx->newObject('modActionDom');
        $rule->set('action',$set->get('action'));
        $rule->set('name',(string)$field->name);
        $rule->set('container','modx-panel-resource');
        $rule->set('rule','fieldTitle');
        $rule->set('value',$label);
        $rule->set('constraint_class',$set->get('constraint_class'));
        $rule->set('constraint_field',$set->get('constraint_field'));
        $rule->set('constraint',$set->get('constraint'));
        $rule->set('active',true);
        if ($action && $action->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }
        $rule->set('rank',4);
        $rules[] = $rule;
    }
    $defaultValue = (string)$field->default_value;
    if (!empty($defaultValue)) {
        $rule = $modx->newObject('modActionDom');
        $rule->set('action',$set->get('action'));
        $rule->set('name',(string)$field->name);
        $rule->set('container','modx-panel-resource');
        $rule->set('rule','fieldDefault');
        $rule->set('value',$defaultValue);
        $rule->set('constraint_class',$set->get('constraint_class'));
        $rule->set('constraint_field',$set->get('constraint_field'));
        $rule->set('constraint',$set->get('constraint'));
        $rule->set('active',true);
        if ($action && $action->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }
        $rule->set('rank',0);
        $rules[] = $rule;
    }
}


/* calculate tabs rules */
foreach ($xml->tabs->tab as $tab) {
    $tabField = $modx->getObject('modActionField',array(
        'action' => $action->get('id'),
        'name' => (string)$tab->name,
        'type' => 'tab',
    ));
    /* if creating a new tab */
    $visible = (int)$tab->visible;
    if (empty($tabField) && !empty($visible)) {
        $rule = $modx->newObject('modActionDom');
        $rule->set('action',$set->get('action'));
        $rule->set('name',(string)$tab->name);
        $rule->set('container','modx-resource-tabs');
        $rule->set('rule','tabNew');
        $rule->set('value',(string)$tab->label);
        $rule->set('constraint_class',$set->get('constraint_class'));
        $rule->set('constraint_field',$set->get('constraint_field'));
        $rule->set('constraint',$set->get('constraint'));
        $rule->set('active',true);
        if ($action && $action->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }
        $rule->set('rank',1);
        $rules[] = $rule;
    } else {
    /* otherwise editing an existing one */
        if (empty($visible)) {
            $rule = $modx->newObject('modActionDom');
            $rule->set('action',$set->get('action'));
            $rule->set('name',(string)$tab->name);
            $rule->set('container','modx-panel-resource');
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
            $rules[] = $rule;
        }
        $label = (string)$tab['label'];
        if (!empty($label)) {
            $rule = $modx->newObject('modActionDom');
            $rule->set('action',$set->get('action'));
            $rule->set('name',(string)$tab->name);
            $rule->set('container','modx-panel-resource');
            $rule->set('rule','tabTitle');
            $rule->set('value',$label);
            $rule->set('constraint_class',$set->get('constraint_class'));
            $rule->set('constraint_field',$set->get('constraint_field'));
            $rule->set('constraint',$set->get('constraint'));
            $rule->set('active',true);
            if ($action && $action->get('controller') == 'resource/create') {
                $rule->set('for_parent',true);
            }
            $rule->set('rank',3);
            $rules[] = $rule;
        }
    }
}

/* calculate TV rules */
foreach ($xml->tvs->tv as $tvData) {
    $tv = $modx->getObject('modTemplateVar',array(
        'name' => (string)$tvData->name,
    ));
    if (empty($tv)) {
        $modx->log(modX::LOG_LEVEL_ERROR,'FC Import Error: Could not find TV with name: "'.$tvData->name.'".');
        continue;
    }

    $visible = (int)$tvData->visible;
    if (empty($visible)) {
        $rule = $modx->newObject('modActionDom');
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
        $rules[] = $rule;
    }
    $label = (string)$tvData->label;
    if (!empty($label)) {
        $rule = $modx->newObject('modActionDom');
        $rule->set('action',$set->get('action'));
        $rule->set('name','tv'.$tv->get('id'));
        $rule->set('container','modx-panel-resource');
        $rule->set('rule','tvTitle');
        $rule->set('value',$label);
        $rule->set('constraint_class',$set->get('constraint_class'));
        $rule->set('constraint_field',$set->get('constraint_field'));
        $rule->set('constraint',$set->get('constraint'));
        $rule->set('active',true);
        if ($action && $action->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }
        $rule->set('rank',11);
        $rules[] = $rule;
    }
    $defaultValue = (string)$tvData->default_value;
    $defaultText = $tv->get('default_text');
    if (strcmp($defaultText,$defaultValue) !== 0) {
        $rule = $modx->newObject('modActionDom');
        $rule->set('action',$set->get('action'));
        $rule->set('name','tv'.$tv->get('id'));
        $rule->set('container','modx-panel-resource');
        $rule->set('rule','tvDefault');
        $rule->set('value',$defaultValue);
        $rule->set('constraint_class',$set->get('constraint_class'));
        $rule->set('constraint_field',$set->get('constraint_field'));
        $rule->set('constraint',$set->get('constraint'));
        $rule->set('active',true);
        if ($action && $action->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }
        $rule->set('rank',10);
        $rules[] = $rule;
    }
    $tab = (string)$tvData->tab;
    if (!empty($tab) && $tab != 'modx-panel-resource-tv') {
        $rule = $modx->newObject('modActionDom');
        $rule->set('action',$set->get('action'));
        $rule->set('name','tv'.$tv->get('id'));
        $rule->set('container','modx-panel-resource');
        $rule->set('rule','tvMove');
        $rule->set('value',$tab);
        $rule->set('constraint_class',$set->get('constraint_class'));
        $rule->set('constraint_field',$set->get('constraint_field'));
        $rule->set('constraint',$set->get('constraint'));
        $rule->set('active',true);
        if ($action && $action->get('controller') == 'resource/create') {
            $rule->set('for_parent',true);
        }
        /* add 20 to rank to make sure happens after tab create */
        $rank = 20+((int)$tvData->tab_rank);
        $rule->set('rank',$rank);
        $rules[] = $rule;
    }
}
$set->addMany($rules);

if (!$set->save()) {
    return $modx->error->failure($modx->lexicon('set_import_err_save'));
}

return $modx->error->success();