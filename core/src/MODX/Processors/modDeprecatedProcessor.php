<?php

namespace MODX\Processors;

/**
 * A utility class for pre-2.2-style, or flat file, processors.
 *
 * @package modx
 */
class modDeprecatedProcessor extends modProcessor
{
    /**
     * Rather than load a class for processing, include the processor file directly.
     *
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $modx =& $this->modx;
        $scriptProperties = $this->getProperties();
        $o = include $this->path;

        return $o;
    }
}