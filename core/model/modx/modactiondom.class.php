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
 * Adds custom manager adjustments based upon modAction objects
 *
 * @property int $set The modFormCustomizationSet this rule belongs to
 * @property int $action The modAction this rule occurs on
 * @property string $name The field this rule applies to
 * @property string $description A description of this rule, or alternate text
 * @property string $container The containing object the rule applies to
 * @property string $rule The type of rule
 * @property string $value The value stored for this rule
 * @property boolean $for_parent Whether or not to apply this rule to the parent object in question
 * @property int $rank The rank in which this rule should be applied
 *
 * @see modFormCustomizationSet
 * @package modx
 */
class modActionDom extends modAccessibleSimpleObject {

    /**
     * Apply the rule to the current page.
     *
     * @access public
     * @param int|string $objId The PK of the object that the rule is being applied to.
     * @return string The generated code that applies the rule.
     */
    public function apply($objId = '') {
        $rule = '';
        $encoding = $this->xpdo->getOption('modx_charset',null,'UTF-8');

        /* now switch by types of rules */
        switch ($this->get('rule')) {
            case 'fieldVisible':
                if (!$this->get('value')) {
                    $fields = explode(',',$this->get('name'));
                    $rule = 'MODx.hideField("'.$this->get('container').'",'.$this->xpdo->toJSON($fields).');';
                }
                break;
            case 'fieldLabel':
            case 'fieldTitle':
                $fields = explode(',',$this->get('name'));
                $values = explode(',',$this->get('value'));
                foreach ($values as &$value) {
                    $value = htmlspecialchars($value,ENT_COMPAT,$encoding);
                }
                $rule = 'MODx.renameLabel("'.$this->get('container').'",'.$this->xpdo->toJSON($fields).','.$this->xpdo->toJSON($values).');';
                break;
            case 'panelTitle':
            case 'tabTitle':
            case 'tabLabel':
                $rule = 'MODx.renameTab("'.$this->get('name').'","'.htmlspecialchars($this->get('value'),ENT_COMPAT,$encoding).'");';
                break;
            case 'tabVisible':
                if (!$this->get('value')) {
                    $tabs = explode(',',$this->get('name'));
                    $rule = '';
                    foreach ($tabs as $tab) {
                        $tab = trim($tab);
                        $rule .= 'MODx.hideRegion("'.$this->get('container').'","'.$tab.'");';
                    }
                }
                break;
            case 'tabNew':
                $title = $this->get('value');
                $rule = 'MODx.addTab("'.$this->get('container').'",{title:"'.htmlspecialchars($title,ENT_COMPAT,$encoding).'",id:"'.$this->get('name').'"});';
                break;
            case 'tvMove':
                $tvs = explode(',',$this->get('name'));
                $rule = 'MODx.moveTV('.$this->xpdo->toJSON($tvs).',"'.$this->get('value').'");';
                break;
            default: break;
        }

        return $rule;
    }
}
