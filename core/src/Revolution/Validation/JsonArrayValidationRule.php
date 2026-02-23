<?php

namespace MODX\Revolution\Validation;

use xPDO\Validation\xPDOValidationRule;

/**
 * Validates that a field value is an array or a JSON string that decodes to an array.
 *
 * @package MODX\Revolution\Validation
 */
class JsonArrayValidationRule extends xPDOValidationRule
{
    /**
     * @param mixed $value The field value.
     * @param array $options Rule options.
     * @return bool True if valid.
     */
    public function isValid($value, array $options = [])
    {
        if (is_array($value)) {
            return true;
        }
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return true;
            }
        }
        $this->validator->addMessage($this->field, $this->name, $this->message);
        return false;
    }
}
