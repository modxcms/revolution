<?php

namespace MODX\Processors\Resource;

use MODX\Processors\modProcessor;

/**
 * Retrieves a string and returns it transliterated to use in various applications but mainly for real-time alias
 *
 * @param string $string The string to transliterate
 *
 * @return string
 *
 * @package modx
 * @subpackage processors.resource
 */
class Translit extends modProcessor
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
            'transliteration' => $this->modx->call('modResource', 'filterPathSegment', [&$this->modx, $string]),
        ];

        return $this->success('', $transliteration);
    }
}
