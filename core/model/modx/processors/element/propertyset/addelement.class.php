<?php
/**
 * Adds an element to a Property Set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */

class modPropertySetAddElementProcessor extends modObjectProcessor {
    public $classKey = 'modElementPropertySet';
    public $objectType = 'propertyset';
    public $permission = 'save_propertyset';
    public $languageTopics = array('propertyset', 'element');


    public function initialize() {
        $elementClass = $this->getProperty('element_class');
        $elementId = $this->getProperty('element');
        if (empty($elementClass) || empty($elementId)) {
            return $this->modx->lexicon('element_err_ns');
        }

        /* grab element */
        $element = $this->modx->getObject($elementClass, $elementId);
        if (!$element) {
            return $this->modx->lexicon('element_err_nf');
        }

        /* grab the modPropertySet */
        $propertySetId = (int) $this->getProperty('propertyset');
        if (!$propertySetId) {
            return $this->modx->lexicon($this->objectType.'_err_ns');
        }

        $set = $this->modx->getObject('modPropertySet', $propertySetId);
        if (!$set) {
            return $this->modx->lexicon($this->objectType.'_err_nfs', array('id' => $propertySetId));
        }
        $this->setProperty('property_set', $propertySetId);
        $this->unsetProperty('propertyset');
        $this->unsetProperty('action');

        $this->object = $this->modx->newObject($this->classKey);

        return parent::initialize();
    }

    /**
     * Log add element to property set
     * @return void
     */
    public function logManagerAction() {
        $item = $this->object->get('element_class') . ' ' . $this->object->get('element') .
            ', modPropertySet ' . $this->object->get('property_set');
        $this->modx->logManagerAction($this->objectType.'_element_add', $this->classKey, $item);
    }

    public function process() {
        $this->object->fromArray($this->getProperties(),'',true);
        if ($this->object->save() === false) {
            return $this->failure($this->modx->lexicon($this->objectType.'_err_element_add'));
        }
        $this->logManagerAction();
        return $this->cleanup();
    }

    /**
     * Return the success message
     * @return array
     */
    public function cleanup() {
        return $this->success('', $this->object);
    }

}

return 'modPropertySetAddElementProcessor';


/*if ($pse->save() === false) {
    return $modx->error->failure($modx->lexicon('propertyset_err_element_add'));
}*/
