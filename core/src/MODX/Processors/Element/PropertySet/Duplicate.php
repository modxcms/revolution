<?php

namespace MODX\Processors\Element\PropertySet;

use MODX\modElementPropertySet;
use MODX\Processors\modObjectDuplicateProcessor;

/**
 * Duplicates a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
class Duplicate extends modObjectDuplicateProcessor
{
    public $objectType = 'propertyset';
    public $classKey = 'modPropertySet';
    public $permission = 'new_propertyset';
    public $languageTopics = ['propertyset', 'category'];


    public function beforeSave()
    {
        $copyEls = (bool)$this->getProperty('copyels', false);
        if ($copyEls) {
            $els = $this->object->getMany('Elements');
            $pses = [];
            /** @var modElementPropertySet $el */
            foreach ($els as $el) {
                /** @var modElementPropertySet $pse */
                $pse = $this->modx->newObject('modElementPropertySet');
                $pse->set('element_class', $el->get('element_class'));
                $pse->set('element', $el->get('element'));
                $pses[] = $pse;
            }
            $this->newObject->addMany($pses);
        }

        return parent::beforeSave();
    }
}
