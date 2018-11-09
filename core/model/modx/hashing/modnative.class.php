<?php
/**
 * This file contains a modHash implementation of PHP's native password_hash/password_verify handling.
 * @package modx
 * @subpackage hashing
 */

if (!function_exists('password_hash')) {
    require MODX_CORE_PATH . 'model/lib/password.php';
}

/**
 * A PHP native password_hash/password_verify implementation of modHash.
 *
 * {@inheritdoc}
 *
 * @package modx
 * @subpackage hashing
 */
class modNative extends modHash {
    /**
     * Generates the hash for the provided string using PHP's password_hash function.
     *
     * @param string $string A string to generate a secure hash from.
     * @param array $options An array of options to be passed to the hash implementation.
     * @return mixed The hash result or false on failure.
     */
    public function hash($string, array $options = array()) {

        return password_hash($string, PASSWORD_DEFAULT);
    }

    /**
     * Verifies with PHP's native password_verify function that the provided hash in $expected matches the
     * raw (unhashed) $string.
     *
     * @param string $string
     * @param string $expected
     * @param array $options
     * @return bool
     */
    public function verify($string, $expected, array $options = array())
    {
        return password_verify($string, $expected);
    }
}
