<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modFormCustomizationSet extends xPDOSimpleObject {

    public function getData() {
        $setArray = array();

        /* get fields */
        $c = $this->xpdo->newQuery('modActionField');
        $c->innerJoin('modActionField','Tab','Tab.name = modActionField.tab');
        $c->select(array(
            'modActionField.*',
            'Tab.rank AS tab_rank',
        ));
        $c->where(array(
            'action' => $this->get('action'),
            'type' => 'field',
        ));
        $c->sortby('Tab.rank','ASC');
        $c->sortby('modActionField.rank','ASC');
        $fields = $this->xpdo->getCollection('modActionField',$c);

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
                        $tvArray['rank'] = ((int)$rule->get('rank'))-10;
                        if ($tvArray['rank'] < 0) $tvArray['rank'] = 0;
                        break;
                }
            }

            $setArray['tvs'][] = $tvArray;
        }

        /* get tabs */
        $c = $this->xpdo->newQuery('modActionField');
        $c->where(array(
            'action' => $this->get('action'),
            'type' => 'tab',
        ));
        $c->sortby('rank','ASC');
        $tabs = $this->xpdo->getCollection('modActionField',$c);

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