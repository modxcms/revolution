<?php
/**
 * Removes an element from a Property Set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */

class modPropertySetRemoveElementProcessor extends modObjectRemoveProcessor {
    public $objectType = 'propertyset';
    public $classKey = 'modElementPropertySet';
    public $permission = 'delete_propertyset';
    public $languageTopics = array('propertyset', 'element');

    /**
     * {@inheritdoc}
     * @return bool|null|string
     */
    public function initialize() {
        $elementClass = $this->getProperty('element_class', '');
        $elementId = (int)$this->getProperty('element', 0);
        if (empty($elementClass) || !$elementId) {
            return $this->modx->lexicon('element_err_ns');
        }

        $propertySetId = (int)$this->getProperty('propertyset', 0);
        if (!$propertySetId) {
            return $this->modx->lexicon($this->objectType.'_err_ns');
        }

        $this->object = $this->modx->getObject($this->classKey, array(
            'element' => $elementId,
            'element_class' => $elementClass,
            'property_set' => $propertySetId,
        ));
        if (!$this->object) {
            return $this->modx->lexicon($this->objectType.'_err_element_nf');
        }

        if ($this->checkRemovePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('remove')) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }

    /**
     * {@inheritdoc}
     * @return array|string
     */
    public function process() {
        if ($this->object->remove() === false) {
            return $this->failure($this->modx->lexicon($this->objectType.'_err_element_remove'));
        }
        $this->logManagerAction();
        return $this->success('', $this->object);
    }

    /**
     * Log the removal of element from a property set manager action
     * @return void
     */
    public function logManagerAction() {
        $item = $this->object->get('element_class') . ' ' . $this->object->get('element') .
            ', modPropertySet ' . $this->object->get('property_set');
        $this->modx->logManagerAction($this->objectType.'_element_remove', $this->classKey, $item);
    }
}

return 'modPropertySetRemoveElementProcessor';
