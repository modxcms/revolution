<?php
/**
 * Represents a category for organizing modElement instances.
 *
 * @package modx
 */
class modCategory extends xPDOSimpleObject {

    /**
     * @var array A list of invalid characters in the name of an Element.
     * @access protected
     */
    protected $_invalidCharacters = array('!','@','#','$','%','^','&','*',
    '(',')','+','=','[',']','{','}','\'','"',':',';','\\','/','<','>','?'
    ,',','`','~');

    /**
     * Overrides xPDOObject::set to strip invalid characters from element names.
     *
     * {@inheritDoc}
     */
    public function set($k, $v= null, $vType= '') {
        switch ($k) {
            case 'category':
                $v = str_replace($this->_invalidCharacters,'',$v);
                break;
            default: break;
        }
        return parent::set($k,$v,$vType);
    }

    /**
     * Overrides xPDOObject::save to fire modX-specific events
     *
     * {@inheritDoc}
     */
    public function save($cacheFlag = null) {
        $isNew = $this->isNew();
        
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnCategoryBeforeSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'category' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }
        $saved = parent :: save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnCategorySave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'category' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }

        return $saved;
    }

    /**
     * Overrides xPDOObject::remove to reset all Element categories back to 0
     * and fire modX-specific events.
     *
     * {@inheritDoc}
     */
    public function remove(array $ancestors = array()) {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnCategoryBeforeRemove',array(
                'category' => &$this,
                'ancestors' => $ancestors,
            ));
        }
        $removed = parent :: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $elementClasses = array(
                'modChunk',
                'modPlugin',
                'modSnippet',
                'modTemplate',
                'modTemplateVar',
            );
            foreach ($elementClasses as $classKey) {
                $elements = $this->xpdo->getCollection($classKey,array('category' => $this->get('id')));
                foreach ($elements as $element) {
                    $element->set('category',0);
                    $element->save();
                }
            }

            $this->xpdo->invokeEvent('OnCategoryRemove',array(
                'category' => &$this,
                'ancestors' => $ancestors,
            ));
        }

        return $removed;
    }
}