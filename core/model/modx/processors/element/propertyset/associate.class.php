<?php
include_once dirname(__FILE__).'/addelement.class.php';
/**
 * Associates a property set to an element, or creates a property set
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */

class modPropertySetAssociateProcessor extends modPropertySetAddElementProcessor {

    /**
     * Grab Property Set to check if it exists or create new and get its ID
     * @param $propertySetId
     * @return bool|null|string
     */
    public function getPropertySet(&$propertySetId) {
        // set up proper field names
        $this->setProperty($this->elementKey, $this->getProperty('elementId'));
        $this->setProperty($this->element_class, $this->getProperty('elementType'));
        $this->unsetProperty('elementId');
        $this->unsetProperty('elementType');

        $isNew = $this->getProperty('propertyset_new', false);
        $isNew = ($isNew == 'false') ? false : $isNew;
        if (!$isNew) {
            return parent::getPropertySet($propertySetId);
        }

        $setName = trim($this->getProperty('name', ''));
        if (empty($setName)) {
            return $this->modx->lexicon($this->objectType.'_err_ns_name');
        }

        /* if property set already exists with that name */
        $ae = $this->modx->getObject('modPropertySet',array('name' => $setName));
        if ($ae) {
            return $this->modx->lexicon($this->objectType.'_err_ae');
        }

        $set = $this->modx->newObject('modPropertySet');
        $set->set('name', $setName);
        $set->set('description', $this->getProperty('description', ''));
        if ($set->save() === false) {
            return $this->modx->lexicon($this->objectType.'_err_associate');
        }
        $propertySetId = $set->get('id');

        return true;
    }
}

return 'modPropertySetAssociateProcessor';
