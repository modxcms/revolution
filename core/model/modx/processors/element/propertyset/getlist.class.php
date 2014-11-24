<?php
/**
 * Grabs a list of property sets.
 *
 * @param integer $elementId (optional) If set, will only grab property sets for
 * that element. Will also add a 'default' property set with the element's
 * default properties.
 * @param string $elementType (optional) The class key of the prior-mentioned
 * element.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */

class modPropertySetGetListProcessor extends modObjectGetListProcessor {
    public $objectType = 'propertyset';
    public $classKey = 'modPropertySet';
    public $permission = 'view_propertyset';
    public $languageTopics = array('propertyset');

    public $elementId;
    public $elementType;
    public $showNotAssociated;

    /**
     * {@inheritdoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $this->elementId = $this->getProperty('elementId', false);
        $this->elementType = $this->getProperty('elementType', false);

        $c->leftJoin('modElementPropertySet','Elements', array(
            'Elements.element_class'=> $this->elementType,
            'Elements.element'=> $this->elementId,
            'Elements.property_set = modPropertySet.id'
        ));

        $this->showNotAssociated = (bool)$this->getProperty('showNotAssociated', false);
        $showAssociated = (bool)$this->getProperty('showAssociated', false);

        if ($this->showNotAssociated) {
            $c->where(array(
                'Elements.property_set' => null,
            ));
        } else if ($showAssociated) {
            $c->where(array(
                'Elements.property_set:!=' => null,
            ));
        }

        return $c;
    }

    /**
     * If limiting to an Element, get default properties
     * @param array $list
     * @return array
     */
    public function beforeIteration(array $list) {
        if ($this->elementId && $this->elementType && !$this->showNotAssociated) {
            $properties = array();
            $element = $this->modx->getObject($this->elementType, $this->elementId);
            if ($element) {
                $properties = $element->get('properties');
                if (!is_array($properties)) $properties = array();
            }
            $list[] = array('id' => 0, 'name' => $this->modx->lexicon('default'), 'description' => '', 'properties' => $properties);
        }
        return $list;
    }
}

return 'modPropertySetGetListProcessor';
