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
    ,' ',',','`','~');

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
     * Overrides xPDOObject::remove to reset all Element categories back to 0.
     *
     * {@inheritDoc}
     */
    public function remove(array $ancestors = array()) {
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
                $elements = $this->xpdo->getCollection($classKey,array('category' => $this->get('id')));
                foreach ($elements as $element) {
                    $element->set('category',0);
                    $element->save();
                }
            }
        }
        return $removed;
    }
}