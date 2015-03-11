<?php
/**
 * Duplicates a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */

class modPropertySetDuplicateProcessor extends modObjectDuplicateProcessor {
    public $objectType = 'propertyset';
    public $classKey = 'modPropertySet';
    public $permission = 'new_propertyset';
    public $languageTopics = array('propertyset', 'category');

    public function beforeSave() {
        $copyEls = (bool)$this->getProperty('copyels', false);
        if ($copyEls) {
            $els = $this->object->getMany('Elements');
            $pses = array();
            foreach ($els as $el) {
                $pse = $this->modx->newObject('modElementPropertySet');
                $pse->set('element_class',$el->get('element_class'));
                $pse->set('element',$el->get('element'));
                $pses[] = $pse;
            }
            $this->newObject->addMany($pses);
        }

        return parent::beforeSave();
    }
}

return 'modPropertySetDuplicateProcessor';
