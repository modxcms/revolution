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
 * A PBKDF2 implementation of modHash.
 *
 * {@inheritdoc}
 *
 * @package modx
 * @subpackage hashing
 */
class modPBKDF2 extends modHash {
    /**
     * Generate a hash of a string using the RSA PBKDFA2 specification.
     *
     * The following options are available:
     *  - salt (required): a valid, non-empty string to salt the hashes
     *  - iterations: the number of iterations per block, default is 1000 (< 1000 not recommended)
     *  - derived_key_length: the size of the derived key to generate, default is 32
     *  - algorithm: the hash algorithm to use, default is sha256
     *  - raw_output: if true, returns binary output, otherwise derived key is base64_encode()'d; default is false
     *
     * @param string $string A string to generate a secure hash from.
     * @param array $options An array of options to be passed to the hash implementation.
     * @return mixed The hash result or false on failure.
     */
    public function hash($string, array $options = array()) {
        $derivedKey = false;
        $salt = $this->getOption('salt', $options, false);
        if (is_string($salt) && strlen($salt) > 0) {
            $iterations = (integer) $this->getOption('iterations', $options, 1000);
            $derivedKeyLength = (integer) $this->getOption('derived_key_length', $options, 32);
            $algorithm = $this->getOption('algorithm', $options, 'sha256');

            $hashLength = strlen(hash($algorithm, null, true));
            $keyBlocks = ceil($derivedKeyLength / $hashLength);
            $derivedKey = '';
            for ($block = 1; $block <= $keyBlocks; $block++) {
                $hashBlock = $hb = hash_hmac($algorithm, $salt . pack('N', $block), $string, true);
                for ($blockIteration = 1; $blockIteration < $iterations; $blockIteration++) {
                    $hashBlock ^= ($hb = hash_hmac($algorithm, $hb, $string, true));
                }
                $derivedKey .= $hashBlock;
            }
            $derivedKey = substr($derivedKey, 0, $derivedKeyLength);
            if (!$this->getOption('raw_output', $options, false)) {
                $derivedKey = base64_encode($derivedKey);
            }
        } else {
            $this->host->modx->log(modX::LOG_LEVEL_ERROR, "PBKDF2 requires a valid salt string.", '', __METHOD__, __FILE__, __LINE__);
        }
        return $derivedKey;
    }
}
