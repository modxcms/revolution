<?php
/**
 * This file contains an MD5 modHash implementation.
 * @package modx
 * @subpackage hashing
 */

/**
 * An MD5 implementation of modHash.
 *
 * {@inheritdoc}
 */
class modMD5 extends modHash {
    /**
     * Generate an MD5 hash of the provided string.
     *
     * Options are ignored in this implementation.
     *
     * {@inheritdoc}
     */
    public function hash($string, array $options = array()) {
        return md5($string);
    }
}
