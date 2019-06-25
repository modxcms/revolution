<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Validation;


use xPDO\Validation\xPDOValidator;

/**
 * Custom validation class for modx
 *
 * @package MODX\Revolution\Validation
 */
class modValidator extends xPDOValidator
{
    /**
     * Validate a xPDOObject by the parameters specified
     *
     * @access public
     *
     * @param array $parameters An associative array of config parameters.
     *
     * @return boolean Either true or false indicating valid or invalid.
     */
    public function validate(array $parameters = [])
    {
        $result = parent:: validate($parameters);
        if (!empty($this->messages)) {
            foreach ($this->messages as $k => $v) {
                if (array_key_exists('message', $this->messages[$k])) {
                    $this->messages[$k]['message'] = $this->object->xpdo->lexicon($this->messages[$k]['message']);
                }
            }
        }

        return $result;
    }
}
