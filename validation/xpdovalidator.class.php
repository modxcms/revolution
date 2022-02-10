<?php
/*
 * Copyright 2010-2015 by MODX, LLC.
 *
 * This file is part of xPDO.
 *
 * xPDO is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * xPDO is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * xPDO; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * The base xPDO validation classes.
 *
 * This file contains the base validation classes used by xPDO.
 *
 * @package xpdo
 * @subpackage validation
 */

/**
 * The base validation service class.
 *
 * Extend this class to customize the validation process.
 *
 * @package xpdo
 * @subpackage validation
 */
class xPDOValidator {
    public $object = null;
    public $results = array();
    public $messages = array();

    public function __construct(& $object) {
        $this->object = & $object;
        $this->object->_loadValidation(true);
    }

    /**
     * Executes validation against the object attached to this validator.
     *
     * @param array $parameters A collection of parameters.
     * @return boolean Either true or false indicating valid or invalid.
     */
    public function validate(array $parameters = array()) {
        $validated= false;
        $this->reset();
        $stopOnFail= isset($parameters['stopOnFail']) && $parameters['stopOnFail']
                ? true
                : false;
        $stopOnRuleFail= isset($parameters['stopOnRuleFail']) && $parameters['stopOnRuleFail']
                ? true
                : false;
        if (!empty($this->object->_validationRules)) {
            foreach ($this->object->_validationRules as $column => $rules) {
                $this->results[$column]= $this->object->isValidated($column);
                if (!$this->results[$column]) {
                    $columnResults= array();
                    foreach ($rules as $ruleName => $rule) {
                        $result= false;
                        if (is_array($rule['parameters'])) $rule['parameters']['column'] = $column;
                        switch ($rule['type']) {
                            case 'callable':
                                $callable= $rule['rule'];
                                if (is_callable($callable)) {
                                     $result= call_user_func_array($callable, array($this->object->_fields[$column],$rule['parameters']));
                                    if (!$result) $this->addMessage($column, $ruleName, isset($rule['parameters']['message']) ? $rule['parameters']['message'] : $ruleName . ' failed');
                                } else {
                                    $this->object->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Validation function {$callable} is not a valid callable function.");
                                }
                                break;
                            case 'preg_match':
                                if (is_string($this->object->_fields[$column])) {
                                    $result = (boolean)preg_match($rule['rule'], $this->object->_fields[$column]);
                                }
                                if (!$result) $this->addMessage($column, $ruleName, isset($rule['parameters']['message']) ? $rule['parameters']['message'] : $ruleName . ' failed');
                                if ($this->object->xpdo->getDebug() === true)
                                    $this->object->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "preg_match validation against {$rule['rule']} resulted in " . print_r($result, 1));
                                break;
                            case 'xPDOValidationRule':
                                if ($ruleClass= $this->object->xpdo->loadClass($rule['rule'], '', false, true)) {
                                    if ($ruleObject= new $ruleClass($this, $column, $ruleName)) {
                                        $callable= array($ruleObject, 'isValid');
                                        if (is_callable($callable)) {
                                            $callableParams= array($this->object->_fields[$column], $rule['parameters']);
                                            $result= call_user_func_array($callable, $callableParams);
                                        } else {
                                            $this->object->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Validation rule class {$rule['rule']} does not have an isValid() method.");
                                        }
                                    }
                                } else {
                                    $this->object->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Could not load validation rule class: {$rule['rule']}");
                                }
                                break;
                            default:
                                $this->object->xpdo->log(xPDO::LOG_LEVEL_ERROR, "Unsupported validation rule: " . print_r($rule, true));
                                break;
                        }
                        $columnResults[$ruleName]= $result;
                        if (!$result && $stopOnRuleFail) {
                            break;
                        }
                    }
                    $this->results[$column]= !in_array(false, $columnResults, true) ? true : false;
                    if (!$this->results[$column] && $stopOnFail) {
                        break;
                    }
                }
                if ($this->results[$column]) {
                    $this->object->_validated[$column]= $column;
                }
            }
            if (empty($this->results) || !in_array(false, $this->results, true)) {
                $validated = true;
                if ($this->object->xpdo->getDebug() === true)
                    $this->object->xpdo->log(xPDO::LOG_LEVEL_WARN, "Validation succeeded: " . print_r($this->results, true));
            } elseif ($this->object->xpdo->getDebug() === true) {
                $this->object->xpdo->log(xPDO::LOG_LEVEL_WARN, "Validation failed: " . print_r($this->results, true));
            }
        } else {
            if ($this->object->xpdo->getDebug() === true) $this->object->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Validation called but no rules were found.");
            $validated = true;
        }
        return $validated;
    }

    /**
     * Add a validation message to the stack.
     *
     * @param string $field The name of the field the message relates to.
     * @param string $name The name of the rule the message relates to.
     * @param mixed $message An optional message; the name of the rule is used
     * if no message is specified.
     */
    public function addMessage($field, $name, $message= null) {
        if (empty($message)) $message= $name;
        array_push($this->messages, array(
            'field' => $field,
            'name' => $name,
            'message' => $message,
        ));
    }

    /**
     * Indicates validation messages were generated by validate().
     *
     * @return boolean True if messages were generated.
     */
    public function hasMessages() {
        return (count($this->messages) > 0);
    }

    /**
     * Get the validation messages generated by validate().
     *
     * @return array An array of validation messages.
     */
    public function getMessages() {
        return $this->messages;
    }

    /**
     * Get the validation results generated by validate().
     *
     * @return array An array of boolean validation results.
     */
    public function getResults() {
        return $this->results;
    }

    /**
     * Reset the validation results and messages.
     */
    public function reset() {
        $this->results= array();
        $this->messages= array();
    }
}

/**
 * The base validation rule class.
 *
 * @package xpdo
 * @subpackage validation
 */
class xPDOValidationRule {
    public $validator = null;
    public $field = '';
    public $name = '';
    public $message = '';

    /**
     * Construct a new xPDOValidationRule instance.
     *
     * @param xPDOValidator &$validator A reference to the xPDOValidator executing this rule.
     * @param mixed $field The field being validated.
     * @param mixed $name The identifying name of the validation rule.
     * @param string $message An optional message for rule failure.
     * @return xPDOValidationRule The rule instance.
     */
    public function __construct(& $validator, $field, $name, $message= '') {
        $this->validator = & $validator;
        $this->field = $field;
        $this->name = $name;
        $this->message = (!empty($message) && $message !== '0' ? $message : $name);
    }

    /**
     * The public method for executing a validation rule.
     *
     * Extend this method to provide a reusable validation rule in your xPDOValidator instance.
     *
     * @param mixed $value The value of the field being validated.
     * @param array $options Any options expected by the rule.
     * @return boolean True if the validation rule was passed, otherwise false.
     */
    public function isValid($value, array $options = array()) {
        if (isset($options['message'])) {
            $this->setMessage($options['message']);
        }
        return true;
    }

    /**
     * Set the failure message for the rule.
     *
     * @param string $message A message intended to convey the reason for rule failure.
     */
    public function setMessage($message= '') {
        if (!empty($message) && $message !== '0') {
            $this->message= $message;
        }
    }
}

class xPDOMinLengthValidationRule extends xPDOValidationRule {
    public function isValid($value, array $options = array()) {
        $result= parent :: isValid($value, $options);
        $minLength= isset($options['value']) ? intval($options['value']) : 0;
        $result= (is_string($value) && strlen($value) >= $minLength);
        if ($result === false) {
            $this->validator->addMessage($this->field, $this->name, $this->message);
        }
        return $result;
    }
}
class xPDOMaxLengthValidationRule extends xPDOValidationRule {
    public function isValid($value, array $options = array()) {
        $result= parent :: isValid($value, $options);
        $maxLength= isset($options['value']) ? intval($options['value']) : 0;
        $result= ($maxLength > 0 && is_string($value) && strlen($value) <= $maxLength);
        if ($result === false) {
            $this->validator->addMessage($this->field, $this->name, $this->message);
        }
        return $result;
    }
}
class xPDOMinValueValidationRule extends xPDOValidationRule {
    public function isValid($value, array $options = array()) {
        $result= parent :: isValid($value, $options);
        $minValue= isset($options['value']) ? intval($options['value']) : 0;
        $result= ($value >= $minValue);
        if ($result === false) {
            $this->validator->addMessage($this->field, $this->name, $this->message);
        }
        return $result;
    }
}
class xPDOMaxValueValidationRule extends xPDOValidationRule {
    public function isValid($value, array $options = array()) {
        $result= parent :: isValid($value, $options);
        $maxValue= isset($options['value']) ? intval($options['value']) : 0;
        $result= ($value <= $maxValue);
        if ($result === false) {
            $this->validator->addMessage($this->field, $this->name, $this->message);
        }
        return $result;
    }
}
class xPDOObjectExistsValidationRule extends xPDOValidationRule {
    public function isValid($value, array $options = array()) {
        if (!isset($options['pk']) || !isset($options['className'])) return false;

        $result= parent :: isValid($value, $options);
        $xpdo =& $this->validator->object->xpdo;

        $obj = $xpdo->getObject($options['className'],$options['pk']);
        $result = ($obj !== null);
        if ($result === false) {
            $this->validator->addMessage($this->field, $this->name, $this->message);
        }
        return $result;
    }
}
class xPDOForeignKeyConstraint extends xPDOValidationRule {
    public function isValid($value, array $options = array()) {
        if (!isset($options['alias'])) return false;
        parent :: isValid($value, $options);
        $result= false;
        $obj=& $this->validator->object;
        $xpdo=& $obj->xpdo;

        $fkdef= $obj->getFKDefinition($options['alias']);
        if (isset ($obj->_relatedObjects[$options['alias']])) {
            if (!is_object($obj->_relatedObjects[$options['alias']])) {
                $result= false;
            }
        }

        $criteria= array ($fkdef['foreign'] => $obj->get($fkdef['local']));
        if (isset($fkdef['criteria']['foreign'])) {
            $criteria= array($fkdef['criteria']['foreign'], $criteria);
        }
        if ($object= $xpdo->getObject($fkdef['class'], $criteria)) {
           $result= ($object !== null);
        }
        if ($result === false) {
            $this->validator->addMessage($this->field, $this->name, $this->message);
        }
        return $result;
    }
}
