<?php
/**
 * Gets a list of Form Customization sets.
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
class modFormCustomizationSetGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modFormCustomizationSet';
    public $languageTopics = array('formcustomization');
    public $permission = 'customize_forms';
    public $defaultSortField = 'action';
    public $canEdit = false;
    public $canRemove = false;

    public function initialize() {
        $this->setDefaultProperties(array(
            'profile' => 0,
            'search' => '',
        ));
        $this->canEdit = $this->modx->hasPermission('save');
        $this->canRemove = $this->modx->hasPermission('remove');
        return parent::initialize();
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('modTemplate','Template');
        $profile = $this->getProperty('profile');
        if (!empty($profile)) {
            $c->where(array(
                'profile' => $profile,
            ));
        }
        $search = $this->getProperty('search');
        if (!empty($search)) {
            $c->where(array(
                'modFormCustomizationSet.description:LIKE' => '%'.$search.'%',
                'OR:Template.templatename:LIKE' => '%'.$search.'%',
                'OR:modFormCustomizationSet.constraint_field:LIKE' => '%'.$search.'%',
            ),null,2);
        }
        return $c;
    }
    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->select($this->modx->getSelectColumns('modFormCustomizationSet','modFormCustomizationSet'));
        $c->select(array(
            'Template.templatename',
        ));
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();

        $constraint = $object->get('constraint');
        if (!empty($constraint)) {
            $objectArray['constraint_data'] = $object->get('constraint_class').'.'.$object->get('constraint_field').' = '.$constraint;
        }
        $objectArray['perm'] = array();
        if ($this->canEdit) $objectArray['perm'][] = 'pedit';
        if ($this->canRemove) $objectArray['perm'][] = 'premove';
        
        return $objectArray;
    }
}
return 'modFormCustomizationSetGetListProcessor';