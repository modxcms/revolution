<?php
/**
 * Adds custom manager adjustments based upon modAction objects
 *
 * @package modx
 */
class modActionDom extends modAccessibleSimpleObject {

    /**
     * Apply the rule to the current page.
     *
     * @access public
     * @return string The generated code that applies the rule.
     */
    public function apply() {
        $rule = '';
        /* first check to see if there is any constraints on this rule */
        $constraint = $this->get('constraint');
        $constraintField = $this->get('constraint_field');
        $constraintClass = $this->get('constraint_class');
        if (!empty($constraintClass) && !empty($constraint) && !empty($constraintField)) {
            $obj = $this->xpdo->getObject($constraintClass,$_REQUEST['id']);
            if ($obj instanceof $constraintClass && $obj->get($constraintField) != $constraint) {
                return $rule;
            }
        }

        /* now switch by types of rules */
        switch ($this->get('rule')) {
            case 'fieldVisible':
                if (!$this->get('value')) {
                    $fields = explode(',',$this->get('name'));
                    $rule = 'Ext.getCmp("'.$this->get('container').'").hideField('.$this->xpdo->toJSON($fields).');';
                }
                break;
            case 'fieldLabel':
            case 'fieldTitle':
                $fields = explode(',',$this->get('name'));
                $values = explode(',',$this->get('value'));
                $rule = 'Ext.getCmp("'.$this->get('container').'").setLabel('.$this->xpdo->toJSON($fields).','.$this->xpdo->toJSON($values).');';
                break;
            case 'fieldDefault':
            case 'fieldDefaultValue':
                $rule = 'Ext.getCmp("'.$this->get('container').'").getForm().findField("'.$this->get('name').'").setValue("'.$this->get('value').'");';
                break;
            case 'panelTitle':
            case 'tabTitle':
            case 'tabLabel':

                $rule = 'Ext.getCmp("'.$this->get('name').'").setTitle("'.$this->get('value').'")';
                break;
            case 'tabVisible':
                if (!$this->get('value')) {
                    $rule = 'Ext.getCmp("'.$this->get('container').'").hideTabStripItem("'.$this->get('name').'");';
                }
                break;
            case 'tabNew':
                $title = $this->get('value');
                $rule = 'MODx.addTab("'.$this->get('container').'",{title:"'.$title.'",id:"'.$this->get('name').'"});';
                break;
            case 'tvMove':
                $tvs = explode(',',$this->get('value'));
                $rule = 'MODx.on("ready",function() { MODx.moveTV('.$this->xpdo->toJSON($tvs).',"'.$this->get('name').'"); });';
                break;
            default: break;
        }

        return $rule;
    }
}