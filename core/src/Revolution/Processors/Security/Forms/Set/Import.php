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
use MODX\Revolution\modFormCustomizationProfile;
use MODX\Revolution\modFormCustomizationSet;
use MODX\Revolution\Processors\Model\ImportProcessor;
use MODX\Revolution\modResource;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modTemplateVar;
use MODX\Revolution\modX;

/**
 * Import a Form Customization Set from an XML file
 * @package MODX\Revolution\Processors\Security\Forms\Set
 */
class Import extends ImportProcessor
{
    public $objectType = 'set';
    public $classKey = modFormCustomizationSet::class;
    public $permission = 'customize_forms';
    public $languageTopics = ['formcustomization'];

    public $setName = false;

    /**
     * @return array|bool|string|null
     */
    public function initialize()
    {
        $profileId = (int)$this->getProperty('profile', 0);
        if (!$profileId) {
            return $this->modx->lexicon('profile_err_ns');
        }
        /** @var modFormCustomizationProfile $profile */
        $profile = $this->modx->getObject(modFormCustomizationProfile::class, $profileId);
        if (!$profile) {
            return $this->modx->lexicon('profile_err_nfs', ['id' => $profileId]);
        }

        return parent::initialize();
    }

    /**
     * @return bool|string|null
     */
    public function beforeSave()
    {
        $this->setMainFields();
        $setTemplate = $this->setTemplate();
        if ($setTemplate !== true) {
            return $setTemplate;
        }

        $rules = [];
        $this->setRules($rules);
        $this->setTabRules($rules);
        $this->setTVRules($rules);
        $this->object->addMany($rules);

        return parent::beforeSave();
    }

    public function setMainFields()
    {
        $this->object->set('profile', $this->getProperty('profile'));
        $this->object->set('description', (string)$this->xml->description);
        $this->object->set('constraint_field', (string)$this->xml->constraint_field);
        $this->object->set('constraint', (string)$this->xml->constraint);
        $this->object->set('constraint_class', modResource::class);
        $this->object->set('active', (int)$this->xml->active);
        $this->object->set('action', (string)$this->xml->action);
    }

    /**
     * @return bool|string|null
     */
    public function setTemplate()
    {
        $templatePk = (string)$this->xml->template;
        if (!empty($templatePk)) {
            /** @var modTemplate $template */
            $template = $this->modx->getObject(modTemplate::class, ['templatename' => $templatePk]);
            if (!$template) {
                return $this->modx->lexicon($this->objectType . '_import_template_err_nf');
            }
            $this->object->set('template', $template->get('id'));
        } else {
            $this->object->set('template', 0);
        }

        return true;
    }

    /**
     * @param $name
     * @param $ruleName
     * @param $value
     * @param $rank
     * @param string $container
     * @return modActionDom
     */
    public function setRule($name, $ruleName, $value, $rank, $container = 'modx-panel-resource')
    {
        /** @var modActionDom $rule */
        $rule = $this->modx->newObject(modActionDom::class);
        $rule->set('action', $this->object->get('action'));
        $rule->set('name', $name);
        $rule->set('container', $container);
        $rule->set('rule', $ruleName);
        $rule->set('value', $value);
        $rule->set('constraint_class', $this->object->get('constraint_class'));
        $rule->set('constraint_field', $this->object->get('constraint_field'));
        $rule->set('constraint', $this->object->get('constraint'));
        $rule->set('active', true);
        if ($this->object->get('action') === 'resource/create') {
            $rule->set('for_parent', true);
        }
        $rule->set('rank', $rank);

        return $rule;
    }

    /* set fields FC rules */
    public function setRules(array &$rules)
    {
        foreach ($this->xml->fields->field as $field) {
            $name = (string)$field->name;

            $visible = (int)$field->visible;
            if (empty($visible)) {
                $rules[] = $this->setRule($name, 'fieldVisible', 0, 5);
            }

            $label = (string)$field->label;
            if (!empty($label)) {
                $rules[] = $this->setRule($name, 'fieldTitle', $label, 4);
            }

            $defaultValue = (string)$field->default_value;
            if (!empty($defaultValue)) {
                $rules[] = $this->setRule($name, 'fieldDefault', $defaultValue, 0);
            }
        }
    }

    /* calculate tabs rules */
    public function setTabRules(array &$rules)
    {
        foreach ($this->xml->tabs->tab as $tab) {
            $name = (string)$tab->name;
            $tabField = $this->modx->getObject(modActionField::class, [
                'action' => $this->object->get('action'),
                'name' => $name,
                'type' => 'tab',
            ]);

            /* if creating a new tab */
            $visible = (int)$tab->visible;
            if (empty($tabField) && !empty($visible)) {
                $rules[] = $this->setRule($name, 'tabNew', (string)$tab->label, 1, 'modx-resource-tabs');
            } else {
                /* otherwise editing an existing one */
                if (empty($visible)) {
                    $rules[] = $this->setRule($name, 'tabVisible', 0, 2);
                }

                $label = (string)$tab['label'];
                if (!empty($label)) {
                    $rules[] = $this->setRule($name, 'tabTitle', $label, 3);
                }
            }
        }
    }

    /* calculate TV rules */
    public function setTVRules(array &$rules)
    {
        foreach ($this->xml->tvs->tv as $tvData) {
            /** @var modTemplateVar $tv */
            $tv = $this->modx->getObject(modTemplateVar::class, ['name' => (string)$tvData->name]);
            if (!$tv) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,
                    'FC Import Error: Could not find TV with name: "' . $tvData->name . '".');
                continue;
            }

            $name = $tv->get('id');

            $visible = (int)$tvData->visible;
            if (empty($visible)) {
                $rules[] = $this->setRule($name, 'tvVisible', 0, 12);
            }

            $label = (string)$tvData->label;
            if (!empty($label)) {
                $rules[] = $this->setRule($name, 'tvTitle', $label, 11);
            }

            $defaultValue = (string)$tvData->default_value;
            $defaultText = $tv->get('default_text');
            if (strcmp($defaultText, $defaultValue) !== 0) {
                $rules[] = $this->setRule($name, 'tvDefault', $defaultValue, 10);
            }

            $tab = (string)$tvData->tab;
            if (!empty($tab) && $tab !== 'modx-panel-resource-tv') {
                $rank = 20 + ((int)$tvData->tab_rank);
                $rules[] = $this->setRule($name, 'tvMove', $tab, $rank);
            }
        }
    }
}
