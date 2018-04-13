<?php
/**
 * @package modx
 * @subpackage validation
 */
/**
 * Custom validation class for modx
 *
 * @package modx
 * @subpackage validation
 */
class modValidator extends xPDOValidator {
    /**
     * Validate a xPDOObject by the parameters specified
     *
     * @access public
     * @param array $parameters An associative array of config parameters.
     * @return boolean Either true or false indicating valid or invalid.
     */
    public function validate(array $parameters= array()) {
        $result= parent :: validate($parameters);
        if (!empty($this->messages)) {
            foreach ($this->messages as $k => $v) {
                if (array_key_exists('message',$this->messages[$k])) {
                    $this->messages[$k]['message']= $this->object->xpdo->lexicon($this->messages[$k]['message']);
                }
            }
        }
        return $result;
    }
}
