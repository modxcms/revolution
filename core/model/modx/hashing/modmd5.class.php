<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * An MD5 implementation of modHash.
 *
 * {@inheritdoc}
 *
 * @package modx
 * @subpackage hashing
 */
class modMD5 extends modHash {
    /**
     * Generate a md5 hash of the given string using the provided options.
     *
     * @param string $string A string to generate a secure hash from.
     * @param array $options Ignored. An array of options to be passed to the hash implementation.
     * @return mixed The hash result or false on failure.
     */
    public function hash($string, array $options = array()) {
        return md5($string);
    }
}
