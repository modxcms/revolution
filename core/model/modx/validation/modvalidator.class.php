<?php
/**
 * Custom validation class for modx
 *
 * @package modx
 * @subpackage validation
 */
class modValidator extends xPDOValidator {

    function __construct(& $object) {
        $this->object = & $object;
        $this->object->_loadValidation(true);
    }

    /**
     * Validate a xPDOObject by the parameters specified
     *
     * @access public
     * @param xPDOObject &$object The object to validate
     * @param array $parameters An associative array of config parameters.
     * @return boolean Either true or false indicating valid or invalid.
     */
    public function validate(&$object, array $parameters= array()) {
        $result= parent :: validate($object,$parameters);
        if (!empty($this->messages)) {
            reset($this->messages);
            while (list ($k, $v)= each($this->messages)) {
                if (array_key_exists('message',$this->messages[$k])) {
                    $this->messages[$k]['message']= $this->object->xpdo->lexicon($this->messages[$k]['message']);
                }
            }
        }
        return $result;
    }
}