<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Hashing;

/**
 * A PHP native password_hash/password_verify implementation of modHash.
 *
 * {@inheritdoc}
 *
 * @package MODX\Revolution\Hashing
 */
class modNative extends modHash
{
    /**
     * Generates the hash for the provided string using PHP's password_hash function.
     *
     * @param string $string  A string to generate a secure hash from.
     * @param array  $options An array of options to be passed to the hash implementation.
     *
     * @return mixed The hash result or false on failure.
     */
    public function hash($string, array $options = [])
    {

        return password_hash($string, PASSWORD_DEFAULT);
    }

    /**
     * Verifies with PHP's native password_verify function that the provided hash in $expected matches the
     * raw (unhashed) $string.
     *
     * @param string $string
     * @param string $expected
     * @param array  $options
     *
     * @return bool
     */
    public function verify($string, $expected, array $options = [])
    {
        return password_verify($string, $expected);
    }
}
