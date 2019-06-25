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
 * An MD5 implementation of modHash.
 *
 * {@inheritdoc}
 *
 * @package MODX\Revolution\Hashing
 */
class modMD5 extends modHash
{
    /**
     * Generate a md5 hash of the given string using the provided options.
     *
     * @param string $string  A string to generate a secure hash from.
     * @param array  $options Ignored. An array of options to be passed to the hash implementation.
     *
     * @return mixed The hash result or false on failure.
     */
    public function hash($string, array $options = [])
    {
        return md5($string);
    }
}
