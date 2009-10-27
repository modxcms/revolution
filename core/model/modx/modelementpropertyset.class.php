<?php
/**
 * Represents a modPropertySet relation to a specific modElement.
 *
 * @package modx
 * @extends xPDOObject
 */
class modElementPropertySet extends xPDOObject {
    /**
     * Returns related modElement instances based on the element_class column.
     *
     * {@inheritdoc}
     */
    public function & getOne($alias, $criteria= null, $cacheFlag= true) {
        if ($alias == 'Element') {
            $criteria = $this->xpdo->newQuery($this->get('element_class'), $criteria);
        }
        $object = parent :: getOne($alias, $criteria, $cacheFlag);
        return $object;
    }
}
