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
    public function apply($objId = '') {
        if (empty($objId)) $objId = $_REQUEST['id'];
        $rule = '';
        $encoding = $this->xpdo->getOption('modx_charset',null,'UTF-8');

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
                foreach ($values as &$value) {
                    $value = htmlspecialchars($value,ENT_COMPAT,$encoding);
                }
                $rule = 'MODx.renameLabel("'.$this->get('container').'",'.$this->xpdo->toJSON($fields).','.$this->xpdo->toJSON($values).');';
                break;
            case 'panelTitle':
            case 'tabTitle':
            case 'tabLabel':
                $rule = 'Ext.getCmp("'.$this->get('name').'").setTitle("'.htmlspecialchars($this->get('value'),ENT_COMPAT,$encoding).'");';
                break;
            case 'tabVisible':
                if (!$this->get('value')) {
                    $tabs = explode(',',$this->get('name'));
                    $rule = '';
                    foreach ($tabs as $tab) {
                        $tab = trim($tab);
                        $rule .= 'MODx.hideTab("'.$this->get('container').'","'.$tab.'");';
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