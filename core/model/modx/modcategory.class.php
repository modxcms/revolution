<?php
/**
 * Represents a category for organizing modElement instances.
 *
 * @package modx
 */
class modCategory extends xPDOSimpleObject {
    function modCategory(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    /**
     * Overrides xPDOObject::remove to reset all Element categories back to 0.
     *
     * {@inheritDoc}
     */
    function remove($ancestors = array()) {
        $removed = parent::remove($ancestors);
        if ($removed) {
            $elementClasses = array(
                'modChunk',
                'modPlugin',
                'modSnippet',
                'modTemplate',
                'modTemplateVar',
            );

            foreach ($elementClasses as $classKey) {
                $elements = $modx->getCollection($classKey,array('category' => $this->get('id')));
                foreach ($elements as $element) {
                    $element->set('category',0);
                    $element->save();
                }
            }
        }
        return $removed;
    }
}