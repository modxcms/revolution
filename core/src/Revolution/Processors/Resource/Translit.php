<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Resource;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResource;

/**
 * Retrieves a string and returns it transliterated to use in various applications but mainly for real-time alias
 *
 * @param string $string The string to transliterate
 * @return string
 */
class Translit extends Processor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view');
    }

    public function process()
    {
        $string = $this->getProperty('string');
        $transliteration = [
            'input' => $string,
            'transliteration' => $this->modx->call(modResource::class, 'filterPathSegment', [&$this->modx, $string]),
        ];

        return $this->success('', $transliteration);
    }
}
