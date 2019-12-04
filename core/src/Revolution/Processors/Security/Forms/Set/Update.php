<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Forms\Set;

use MODX\Revolution\modActionDom;
use MODX\Revolution\modActionField;
use MODX\Revolution\modFormCustomizationSet;
use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\modResource;
use MODX\Revolution\modTemplateVar;

/**
 * Saves a Form Customization Set.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by.
 * @param string $dir (optional) The direction of the sort. Default action.
 * @package MODX\Revolution\Processors\Security\Forms\Set
 */
class Update extends UpdateProcessor
{
    public $classKey = modFormCustomizationSet::class;
    public $languageTopics = ['formcustomization'];
    public $permission = 'customize_forms';
    public $objectType = 'set';

    /** @var array $newRules */
    public $newRules = [];

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->setCheckbox('active');
        return parent::beforeSet();
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->set('constraint_class', modResource::class);
        $actionId = $this->getProperty('action_id');
        if ($actionId !== null) {
            $this->object->set('action', $actionId);
        }

        return parent::beforeSave();
    }

    /**
     * @return bool
     * @throws \xPDO\xPDOException
     */
    public function afterSave()
    {
        $this->clearOldRules();
        $this->setFieldRules();
        $this->setTabRules();
        $this->setTVRules();
        $this->saveNewRules();
        return parent::afterSave();
    }

    /**
     * Clear out the old rules
     * @return void
     */
    public function clearOldRules()
    {
        $oldRules = $this->modx->getCollection(modActionDom::class, [
            'set' => $this->object->get('id')
        ]);
        /** @var modActionDom $oldRule */
        foreach ($oldRules as $oldRule) {
            $oldRule->remove();
        }
    }

    /**
     * Calculate field rules
     * @return void
     * @throws \xPDO\xPDOException
     */
    public function setFieldRules()
    {
        $fields = $this->getProperty('fields');
        if ($fields === null) {
            return;
        }
        $fields = is_array($fields) ? $fields : $this->modx->fromJSON($fields);

        foreach ($fields as $field) {
            if (empty($field['visible'])) {
                /** @var modActionDom $rule */
                $rule = $this->modx->newObject(modActionDom::class);
                $rule->set('set', $this->object->get('id'));
                $rule->set('action', $this->object->get('action'));
                $rule->set('name', $field['name']);
                $rule->set('container', 'modx-panel-resource');
                $rule->set('rule', 'fieldVisible');
                $rule->set('value', 0);
                $rule->set('constraint_class', $this->object->get('constraint_class'));
                $rule->set('constraint_field', $this->object->get('constraint_field'));
                $rule->set('constraint', $this->object->get('constraint'));
                $rule->set('active', true);
                if ($this->object->get('action') === 'resource/create') {
                    $rule->set('for_parent', true);
                }
                $rule->set('rank', 5);
                $this->newRules[] = $rule;
            }
            if (!empty($field['label'])) {
                $rule = $this->modx->newObject(modActionDom::class);
                $rule->set('set', $this->object->get('id'));
                $rule->set('action', $this->object->get('action'));
                $rule->set('name', $field['name']);
                $rule->set('container', 'modx-panel-resource');
                $rule->set('rule', 'fieldTitle');
                $rule->set('value', $field['label']);
                $rule->set('constraint_class', $this->object->get('constraint_class'));
                $rule->set('constraint_field', $this->object->get('constraint_field'));
                $rule->set('constraint', $this->object->get('constraint'));
                $rule->set('active', true);
                if ($this->object->get('action') === 'resource/create') {
                    $rule->set('for_parent', true);
                }
                $rule->set('rank', 4);
                $this->newRules[] = $rule;
            }
            if (isset($field['default_value']) && $field['default_value'] !== '') {
                $rule = $this->modx->newObject(modActionDom::class);
                $rule->set('set', $this->object->get('id'));
                $rule->set('action', $this->object->get('action'));
                $rule->set('name', $field['name']);
                $rule->set('container', 'modx-panel-resource');
                $rule->set('rule', 'fieldDefault');
                $rule->set('value', $field['default_value']);
                $rule->set('constraint_class', $this->object->get('constraint_class'));
                $rule->set('constraint_field', $this->object->get('constraint_field'));
                $rule->set('constraint', $this->object->get('constraint'));
                $rule->set('active', true);
                if ($this->object->get('action') === 'resource/create') {
                    $rule->set('for_parent', true);
                }
                $rule->set('rank', 0);
                $this->newRules[] = $rule;
            }
        }
    }

    /**
     * Calculate tab rules
     * @return void
     * @throws \xPDO\xPDOException
     */
    public function setTabRules()
    {
        $tabs = $this->getProperty('tabs');
        if ($tabs === null) {
            return;
        }
        $tabs = is_array($tabs) ? $tabs : $this->modx->fromJSON($tabs);
        $action = $this->object->get('action');

        // If the action ends in /* (wildcard rule), we assume tabs that exist for an update action
        if (substr($action, -2) === '/*') {
            $action = str_replace('/*', '/update', $action);
        }

        foreach ($tabs as $tab) {
            $tabField = $this->modx->getObject(modActionField::class, [
                'action' => $action,
                'name' => $tab['name'],
                'type' => 'tab',
            ]);
            /* if creating a new tab */
            if (empty($tabField) && !empty($tab['visible'])) {
                /** @var modActionDom $rule */
                $rule = $this->modx->newObject(modActionDom::class);
                $rule->set('set', $this->object->get('id'));
                $rule->set('action', $this->object->get('action'));
                $rule->set('name', $tab['name']);
                $rule->set('container', 'modx-resource-tabs');
                $rule->set('rule', 'tabNew');
                $rule->set('value', $tab['label']);
                $rule->set('constraint_class', $this->object->get('constraint_class'));
                $rule->set('constraint_field', $this->object->get('constraint_field'));
                $rule->set('constraint', $this->object->get('constraint'));
                $rule->set('active', true);
                if ($this->object->get('action') === 'resource/create') {
                    $rule->set('for_parent', true);
                }
                $rule->set('rank', 1);
                $this->newRules[] = $rule;
            } else {
                /* otherwise editing an existing one */
                if (empty($tab['visible'])) {
                    $rule = $this->modx->newObject(modActionDom::class);
                    $rule->set('set', $this->object->get('id'));
                    $rule->set('action', $this->object->get('action'));
                    $rule->set('name', $tab['name']);
                    $rule->set('container', 'modx-resource-tabs');
                    $rule->set('rule', 'tabVisible');
                    $rule->set('value', 0);
                    $rule->set('constraint_class', $this->object->get('constraint_class'));
                    $rule->set('constraint_field', $this->object->get('constraint_field'));
                    $rule->set('constraint', $this->object->get('constraint'));
                    $rule->set('active', true);
                    if ($this->object->get('action') === 'resource/create') {
                        $rule->set('for_parent', true);
                    }
                    $rule->set('rank', 2);
                    $this->newRules[] = $rule;
                }
                if (!empty($tab['label'])) {
                    $rule = $this->modx->newObject(modActionDom::class);
                    $rule->set('set', $this->object->get('id'));
                    $rule->set('action', $this->object->get('action'));
                    $rule->set('name', $tab['name']);
                    $rule->set('container', 'modx-resource-tabs');
                    $rule->set('rule', 'tabTitle');
                    $rule->set('value', $tab['label']);
                    $rule->set('constraint_class', $this->object->get('constraint_class'));
                    $rule->set('constraint_field', $this->object->get('constraint_field'));
                    $rule->set('constraint', $this->object->get('constraint'));
                    $rule->set('active', true);
                    if ($this->object->get('action') === 'resource/create') {
                        $rule->set('for_parent', true);
                    }
                    $rule->set('rank', 3);
                    $this->newRules[] = $rule;
                }
            }
        }
    }

    /**
     * Calculate the TV rules
     * @return void
     * @throws \xPDO\xPDOException
     */
    public function setTVRules()
    {
        $tvs = $this->getProperty('tvs');
        if ($tvs === null) {
            return;
        }
        $tvs = is_array($tvs) ? $tvs : $this->modx->fromJSON($tvs);

        foreach ($tvs as $tvData) {
            /** @var modTemplateVar $tv */
            $tv = $this->modx->getObject(modTemplateVar::class, $tvData['id']);
            if ($tv === null) {
                continue;
            }

            if (empty($tvData['visible'])) {
                /** @var modActionDom $rule */
                $rule = $this->modx->newObject(modActionDom::class);
                $rule->set('set', $this->object->get('id'));
                $rule->set('action', $this->object->get('action'));
                $rule->set('name', 'tv' . $tv->get('id'));
                $rule->set('container', 'modx-panel-resource');
                $rule->set('rule', 'tvVisible');
                $rule->set('value', 0);
                $rule->set('constraint_class', $this->object->get('constraint_class'));
                $rule->set('constraint_field', $this->object->get('constraint_field'));
                $rule->set('constraint', $this->object->get('constraint'));
                $rule->set('active', true);
                if ($this->object->get('action') === 'resource/create') {
                    $rule->set('for_parent', true);
                }
                $rule->set('rank', 12);
                $this->newRules[] = $rule;
            }
            if (!empty($tvData['label'])) {
                $rule = $this->modx->newObject(modActionDom::class);
                $rule->set('set', $this->object->get('id'));
                $rule->set('action', $this->object->get('action'));
                $rule->set('name', 'tv' . $tv->get('id'));
                $rule->set('container', 'modx-panel-resource');
                $rule->set('rule', 'tvTitle');
                $rule->set('value', $tvData['label']);
                $rule->set('constraint_class', $this->object->get('constraint_class'));
                $rule->set('constraint_field', $this->object->get('constraint_field'));
                $rule->set('constraint', $this->object->get('constraint'));
                $rule->set('active', true);
                if ($this->object->get('action') === 'resource/create') {
                    $rule->set('for_parent', true);
                }
                $rule->set('rank', 11);
                $this->newRules[] = $rule;
            }
            if ($tv->get('default_text') !== $tvData['default_value']) {
                $rule = $this->modx->newObject(modActionDom::class);
                $rule->set('set', $this->object->get('id'));
                $rule->set('action', $this->object->get('action'));
                $rule->set('name', 'tv' . $tv->get('id'));
                $rule->set('container', 'modx-panel-resource');
                $rule->set('rule', 'tvDefault');
                $rule->set('value', $tvData['default_value']);
                $rule->set('constraint_class', $this->object->get('constraint_class'));
                $rule->set('constraint_field', $this->object->get('constraint_field'));
                $rule->set('constraint', $this->object->get('constraint'));
                $rule->set('active', true);
                if ($this->object->get('action') === 'resource/create') {
                    $rule->set('for_parent', true);
                }
                $rule->set('rank', 10);
                $this->newRules[] = $rule;
            }
            if (!empty($tvData['tab']) && $tvData['tab'] !== 'modx-panel-resource-tv') {
                $rule = $this->modx->newObject(modActionDom::class);
                $rule->set('set', $this->object->get('id'));
                $rule->set('action', $this->object->get('action'));
                $rule->set('name', 'tv' . $tv->get('id'));
                $rule->set('container', 'modx-panel-resource');
                $rule->set('rule', 'tvMove');
                $rule->set('value', $tvData['tab']);
                $rule->set('constraint_class', $this->object->get('constraint_class'));
                $rule->set('constraint_field', $this->object->get('constraint_field'));
                $rule->set('constraint', $this->object->get('constraint'));
                $rule->set('active', true);
                if ($this->object->get('action') === 'resource/create') {
                    $rule->set('for_parent', true);
                }
                /* add 20 to rank to make sure happens after tab create */
                $rank = 20 + ((int)$tvData['rank']);
                $rule->set('rank', $rank);
                $this->newRules[] = $rule;
            }
        }
    }

    /**
     * Save the new rules to the set
     * @return void
     */
    public function saveNewRules()
    {
        /** @var modActionDom $newRule */
        foreach ($this->newRules as $newRule) {
            $newRule->save();
        }
    }
}
