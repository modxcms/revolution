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
 * A collection of rules for the related Form Customization Profile. Can be applied to different "actions", or pages,
 * within the manager. Also can set a constraint on the set so that it only applies under certain circumstances, or
 * with a certain template.
 *
 * @property int $profile The ID of the Profile this set belongs to
 * @property int $action The ID of the modAction this set is tied to
 * @property string $description A description of the set provided by the user
 * @property boolean $active Whether or not this set is active, and will have its rules applied.
 * @property int $template If set to a non-zero value, will only apply rules if the Resource has the specified Template ID
 * @property string $constraint Optional. The value of the constraint_field on constraint_class to check against to see if rules should be applied.
 * @property string $constraint_field Optional. The field name of the constraint_class to check against with the constraint value to see if rules should be applied.
 * @property string $constraint_class Optional. The class of the constraint_field to check against with the constraint value to see if rules should be applied.
 * @see modCustomizationProfile
 * @see modActionDom
 * @package modx
 */
class modFormCustomizationSet extends xPDOSimpleObject {
    /**
     * Get the formatted data for the FC Set
     *
     * @return array
     */
    public function getData() {
        $setArray = array();

        // If the action ends in /* (wildcard rule), we assume the update action to be the "base" action
        $baseAction = $this->get('action');
        if (substr($baseAction, -2) === '/*') {
            $baseAction = str_replace('/*', '/update', $baseAction);
        }

        /* get fields */
        $c = $this->xpdo->newQuery('modActionField');
        $c->innerJoin('modActionField','Tab','Tab.name = modActionField.tab');
        $c->select($this->xpdo->getSelectColumns('modActionField','modActionField'));
        $c->select(array(
            'tab_rank' => 'Tab.rank',
        ));
        $c->where(array(
            'action' => $baseAction,
            'type' => 'field',
        ));
        $c->sortby('Tab.rank','ASC');
        $c->sortby('modActionField.rank','ASC');
        $fields = $this->xpdo->getCollection('modActionField',$c);

        /** @var modActionField $field */
        foreach ($fields as $field) {
            $c = $this->xpdo->newQuery('modActionDom');
            $c->where(array(
                'set' => $this->get('id'),
                'name' => $field->get('name'),
            ));
            $rules = $this->xpdo->getCollection('modActionDom',$c);

            $fieldArray = $field->toArray();
            $fieldArray['visible'] = true;
            $fieldArray['label'] = '';
            $fieldArray['default_value'] = '';
            /** @var modActionDom $rule */
            foreach ($rules as $rule) {
                switch ($rule->get('rule')) {
                    case 'fieldVisible':
                        if ($rule->get('value') == 0) {
                            $fieldArray['visible'] = false;
                        }
                        break;
                    case 'fieldDefault':
                        $fieldArray['default_value'] = $rule->get('value');
                        break;
                    case 'fieldTitle':
                    case 'fieldLabel':
                        $fieldArray['label'] = $rule->get('value');
                        break;
                }
            }
            $setArray['fields'][] = $fieldArray;
        }

        /* get TVs */
        if ($this->get('template')) {
            $c = $this->xpdo->newQuery('modTemplateVar');
            $c->leftJoin('modCategory','Category');
            $c->innerJoin('modTemplateVarTemplate','TemplateVarTemplates');
            $c->select($this->xpdo->getSelectColumns('modTemplateVar', 'modTemplateVar'));
            $c->select(array(
                'Category.category AS category_name',
            ));
            $c->where(array(
                'TemplateVarTemplates.templateid' => $this->get('template'),
            ));
            $c->sortby('Category.category','ASC');
            $c->sortby('TemplateVarTemplates.rank','ASC');
            $tvs = $this->xpdo->getCollection('modTemplateVar',$c);

        } else {
            $c = $this->xpdo->newQuery('modTemplateVar');
            $c->leftJoin('modCategory','Category');
            $c->select($this->xpdo->getSelectColumns('modTemplateVar', 'modTemplateVar'));
            $c->select(array(
                'Category.category AS category_name',
            ));
            $c->sortby('Category.category','ASC');
            $c->sortby('modTemplateVar.name','ASC');
            $tvs = $this->xpdo->getCollection('modTemplateVar',$c);
        }
        /** @var modTemplateVar $tv */
        foreach ($tvs as $tv) {
            $c = $this->xpdo->newQuery('modActionDom');
            $c->where(array(
                'set' => $this->get('id'),
            ));
            $c->andCondition(array(
                'name:=' => 'tv'.$tv->get('id'),
                'OR:value:=' => 'tv'.$tv->get('id'),
            ),null,2);
            $rules = $this->xpdo->getCollection('modActionDom',$c);

            $tvArray = $tv->toArray('',true,true);
            $tvArray['visible'] = true;
            $tvArray['label'] = '';
            $tvArray['default_value'] = $tv->get('default_text');
            $tvArray['tab'] = 'modx-panel-resource-tv';
            $tvArray['rank'] = '';
            /** @var modActionDom $rule */
            foreach ($rules as $rule) {
                switch ($rule->get('rule')) {
                    case 'tvVisible':
                        if ($rule->get('value') == 0) {
                            $tvArray['visible'] = false;
                        }
                        break;
                    case 'tvDefault':
                    case 'tvDefaultValue':
                        $tvArray['default_value'] = $rule->get('value');
                        break;
                    case 'tvTitle':
                    case 'tvLabel':
                        $tvArray['label'] = $rule->get('value');
                        break;
                    case 'tvMove':
                        $tvArray['tab'] = $rule->get('value');
                        /* subtract 20 from rank that have been added in update processor */
                        $tvArray['rank'] = ((int)$rule->get('rank'))-20;
                        if ($tvArray['rank'] < 0) $tvArray['rank'] = 0;
                        break;
                }
            }

            $setArray['tvs'][] = $tvArray;
        }

        /* get tabs */
        $c = $this->xpdo->newQuery('modActionField');
        $c->where(array(
            'action' => $baseAction,
            'type' => 'tab',
        ));
        $c->sortby($this->xpdo->escape('rank'), 'ASC');
        $tabs = $this->xpdo->getCollection('modActionField',$c);

        /** @var modActionField $tab */
        foreach ($tabs as $tab) {
            $c = $this->xpdo->newQuery('modActionDom');
            $c->where(array(
                'set' => $this->get('id'),
                'name' => $tab->get('name'),
            ));
            $rules = $this->xpdo->getCollection('modActionDom',$c);

            $tabArray = $tab->toArray();
            $tabArray['visible'] = true;
            $tabArray['label'] = '';
            foreach ($rules as $rule) {
                switch ($rule->get('rule')) {
                    case 'tabVisible':
                        if ($rule->get('value') == 0) {
                            $tabArray['visible'] = false;
                        }
                        break;
                    case 'tabLabel':
                    case 'tabTitle':
                        $tabArray['label'] = $rule->get('value');
                        break;
                }
            }
            $setArray['tabs'][] = $tabArray;
        }
        $newTabs = $this->xpdo->getCollection('modActionDom',array(
            'set' => $this->get('id'),
            'action' => $this->get('action'),
            'rule' => 'tabNew',
        ));
        foreach ($newTabs as $tab) {
            $tabArray = $tab->toArray();
            $tabArray['visible'] = true;
            $tabArray['label'] = $tab->get('value');
            $tabArray['default_value'] = '';
            $tabArray['type'] = 'new';
            $setArray['tabs'][] = $tabArray;
        }

        return $setArray;
    }
}
